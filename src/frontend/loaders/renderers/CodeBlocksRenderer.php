<?php

/**
 * Renders code blocks (all blocks listed under "blocks" in the JSON).
 * 
 * This version reads the original leading indentation from each line
 * (spaces/tabs) so we don't infer Python indentation. Instead, we display the
 * line's actual indentation as an integer "indent level" (clamped between 0..6).
 * 
 * If you want finer-grained indentation in CSS, reduce the clamp or calculate
 * more precisely (e.g., 2 spaces = indent-level-1).
 */
function renderCodeBlocksSection($codeBlocks) {
    if (!$codeBlocks) return '';

    $blocksHtml = '';
    foreach ($codeBlocks['blocks'] as $block) {
        // No static indentation reset; we read from each line’s leading spaces
        $codeLines = splitIntoCodeLines($block['code']);
        $highlightedCode = "<pre class='code-block'><code>";
        
        foreach ($codeLines as $line) {
            $tokens = tokenizeLine($line['content'], $block['language'] ?? 'python');
            $lineHtml = renderCodeLine($line, $tokens, $block['highlights'] ?? []);
            $highlightedCode .= $lineHtml;
        }
        
        $highlightedCode .= "</code></pre>";

        $blocksHtml .= "
            <div class='code-block-container'>
                <div class='code-block-header'>
                    <h4 class='code-block-title'>{$block['title']}</h4>
                    <button class='copy-button' onclick='copyCode(this)'>
                        <ion-icon name='copy-outline'></ion-icon>
                        <span>Copy</span>
                    </button>
                </div>
                {$highlightedCode}
            </div>
        ";
    }

    return "
        <section class='code-blocks-section detail-section'>
            <div class='code-blocks-content'>
                <h3 class='section-title'>{$codeBlocks['title']}</h3>
                <div class='code-blocks'>
                    {$blocksHtml}
                </div>
            </div>
        </section>
    ";
}

/**
 * Splits code into lines for further tokenization.
 * We also store the full line so we can measure leading indentation.
 */
function splitIntoCodeLines($codeString) {
    // Normalize Windows line endings
    $lines = explode("\n", str_replace("\r\n", "\n", $codeString));
    $processedLines = [];
    
    foreach ($lines as $index => $line) {
        $processedLines[] = [
            'number' => $index + 1,
            'content' => $line,
            'leadingIndent' => measureLeadingIndent($line),
        ];
    }
    
    return $processedLines;
}

/**
 * Measure how many spaces (or tabs) appear at the start of the line,
 * then convert to an "indent level" 0..6 for CSS using, e.g., 4 spaces = 1 level.
 */
function measureLeadingIndent($line) {
    // Count leading whitespace
    $leadingWhite = 0;
    for ($i = 0; $i < strlen($line); $i++) {
        $char = $line[$i];
        if ($char === ' ') {
            $leadingWhite++;
        } elseif ($char === "\t") {
            // treat each tab as 4 spaces
            $leadingWhite += 4;
        } else {
            break;
        }
    }
    // E.g., 4 spaces => indent level 1. Adjust to your preference, maybe 2 = 1.
    $level = (int) floor($leadingWhite / 4);

    // clamp 0..6
    return min(6, max(0, $level));
}

/**
 * Tokenizes a single line of code for syntax highlighting (Python-based).
 * If you just want to preserve the code as-is, skip tokenizing and just HTML-escape it.
 */
function tokenizeLine($line, $language = 'javascript') {
    static $inMultilineComment = false;
    static $commentDelimiter = null;

    error_log("\n=== tokenizeLine START ===");
    error_log("Language: $language");
    error_log("Processing line: $line");

    // Get language-specific components
    $langComponents = match($language) {
        'javascript' => getJavaScriptComponents(),
        'css' => getCssComponents(),
        default => getPythonComponents()
    };

    // Special case: if we're in a multiline comment from previous line
    if ($inMultilineComment && $commentDelimiter) {
        $endPos = strpos($line, $commentDelimiter['end']);
        if ($endPos !== false) {
            $inMultilineComment = false;
            $commentContent = substr($line, 0, $endPos + strlen($commentDelimiter['end']));
            $remainingContent = substr($line, $endPos + strlen($commentDelimiter['end']));
            $commentDelimiter = null;
            
            // Return the comment portion and tokenize the rest
            return array_merge(
                [['type' => 'comment', 'content' => $commentContent]],
                $remainingContent ? tokenizeLine($remainingContent, $language) : []
            );
        }
        return [['type' => 'comment', 'content' => $line]];
    }

    // Reset static variables if we're not in a multiline comment
    if (!$inMultilineComment) {
        $commentDelimiter = null;
    }

    $tokens = [];
    $i = 0;
    $lineLength = strlen($line);

    while ($i < $lineLength) {
        error_log("\nProcessing character at $i: '" . $line[$i] . "'");

        // Check for comments first
        $commentMatch = findComment($line, $i, $language);
        if ($commentMatch) {
            error_log("Found comment: " . json_encode($commentMatch));
            if ($commentMatch['multiline'] && !$commentMatch['closedInLine']) {
                $inMultilineComment = true;
                $commentDelimiter = $commentMatch['delimiter'];
                error_log("Starting multiline comment");
                return [...$tokens, ['type' => 'comment', 'content' => substr($line, $i)]];
            }
            $tokens[] = ['type' => 'comment', 'content' => $commentMatch['content']];
            $i += strlen($commentMatch['content']);
            continue;
        }

        // Check for string literals
        $stringMatch = findStringLiteral($line, $i, $language);
        if ($stringMatch) {
            $tokens = array_merge($tokens, $stringMatch['tokens']);
            $i += $stringMatch['length'];
            continue;
        }

        // Handle spaces
        if ($line[$i] === ' ') {
            $tokens[] = ['type' => 'space', 'content' => ' '];
            $i++;
            continue;
        }

        // Try to match keywords, builtins, operators, or delimiters
        $match = findNextToken($line, $i, $langComponents);
        if ($match) {
            $tokens[] = $match['token'];
            $i += strlen($match['token']['content']);
            continue;
        }

        // For CSS, try to match CSS-specific tokens
        if ($language === 'css') {
            error_log("CSS mode: collecting token at position $i");
            $textBuffer = '';
            $startPos = $i;
            
            // Look ahead to see if this token starts with -- (CSS variable)
            $isVariable = $i < $lineLength - 1 && $line[$i] === '-' && $line[$i + 1] === '-';
            
            // Look ahead for braces to detect selectors
            $colonPos = $i;
            $foundBrace = false;
            while ($colonPos < $lineLength && $line[$colonPos] !== ' ') {
                if ($line[$colonPos] === '{') {
                    $foundBrace = true;
                    break;
                }
                if ($line[$colonPos] === ':' && !$isVariable) {
                    break;
                }
                $colonPos++;
            }
            
            $isSelector = $foundBrace || ($colonPos < $lineLength && strpos(substr($line, $colonPos), '{') !== false);
            
            error_log("Looking ahead - is variable: " . ($isVariable ? 'true' : 'false') . ", is selector: " . ($isSelector ? 'true' : 'false'));
            
            // Collect characters until we hit a boundary
            while ($i < $lineLength) {
                $nextChar = $line[$i];
                error_log("CSS buffer: '$textBuffer', next char: '$nextChar'");
                
                // If we're collecting a CSS variable, continue until : or )
                if ($isVariable) {
                    if ($nextChar === ':' || $nextChar === ')') {
                        error_log("Found CSS variable: '$textBuffer'");
                        $tokens[] = ['type' => 'cssvariable', 'content' => $textBuffer];
                        if ($nextChar === ':') {
                            $tokens[] = ['type' => 'operator', 'content' => ':'];
                        } else if ($nextChar === ')') {
                            $tokens[] = ['type' => 'delimiter', 'content' => ')'];
                        }
                        $i++;
                        break;
                    }
                    $textBuffer .= $nextChar;
                    $i++;
                    continue;
                }
                
                // For non-variable tokens, use normal boundaries
                if ($nextChar === ':') {
                    // We found a property (but not if it's a selector)
                    if (trim($textBuffer) !== '' && !$isSelector) {
                        error_log("Found property: '$textBuffer'");
                        $tokens[] = ['type' => 'property', 'content' => trim($textBuffer)];
                        $tokens[] = ['type' => 'operator', 'content' => ':'];
                    } else {
                        error_log("Found selector with pseudo-class: '$textBuffer'");
                        $textBuffer .= $nextChar;
                    }
                    $i++;
                    if (!$isSelector) break;
                    continue;
                }
                
                if ($nextChar === ' ' || isTokenStart($line, $i, $langComponents)) {
                    break;
                }
                
                $textBuffer .= $nextChar;
                $i++;
            }

            // Process the collected token if it wasn't already handled
            if ($textBuffer !== '' && !$isVariable) {
                error_log("Processing non-variable CSS token: '$textBuffer'");
                $cssToken = processCssToken($textBuffer, $startPos, $line);
                error_log("CSS token result: " . json_encode($cssToken));
                
                if ($cssToken !== null) {
                    if (isset($cssToken['unit'])) {
                        $tokens[] = ['type' => $cssToken['type'], 'content' => $cssToken['content']];
                        $tokens[] = $cssToken['unit'];
                    } else {
                        $tokens[] = $cssToken;
                    }
                } else {
                    error_log("Treating as plain text: '$textBuffer'");
                    $tokens[] = ['type' => 'text', 'content' => $textBuffer];
                }
            }
            continue;
        }

        // If no match found, accumulate characters until we find a token or space
        $textBuffer = '';
        while ($i < $lineLength) {
            $nextChar = $line[$i];
            if ($nextChar === ' ' || isTokenStart($line, $i, $langComponents)) {
                break;
            }
            $textBuffer .= $nextChar;
            $i++;
        }
        if ($textBuffer !== '') {
            $tokens[] = ['type' => 'text', 'content' => $textBuffer];
        }
    }

    error_log("=== tokenizeLine END ===");
    error_log("Final tokens: " . json_encode($tokens));
    return $tokens;
}

function findComment($line, $pos, $language) {
    $commentStarts = match($language) {
        'javascript' => [
            ['start' => '//', 'end' => null, 'type' => 'comment'],
            ['start' => '/*', 'end' => '*/', 'type' => 'comment']
        ],
        'css' => [
            ['start' => '//', 'end' => null, 'type' => 'css-comment'],
            ['start' => '/*', 'end' => '*/', 'type' => 'css-comment']
        ],
        default => [
            ['start' => '#', 'end' => null, 'type' => 'comment'],
            ['start' => '"""', 'end' => '"""', 'type' => 'comment'],
            ['start' => "'''", 'end' => "'''", 'type' => 'comment']
        ]
    };

    foreach ($commentStarts as $comment) {
        if (substr($line, $pos, strlen($comment['start'])) === $comment['start']) {
            // Single line comment
            if (!$comment['end']) {
                return [
                    'content' => substr($line, $pos),
                    'multiline' => false,
                    'type' => $comment['type']
                ];
            }
            
            // Multiline comment
            $endPos = strpos($line, $comment['end'], $pos + strlen($comment['start']));
            return [
                'content' => $endPos !== false ? 
                    substr($line, $pos, $endPos + strlen($comment['end']) - $pos) : 
                    substr($line, $pos),
                'multiline' => true,
                'closedInLine' => $endPos !== false,
                'delimiter' => $comment,
                'type' => $comment['type']
            ];
        }
    }
    return null;
}

function findStringLiteral($line, $pos, $language) {
    $quotes = $language === 'javascript' ? ['"', "'", '`'] : ['"', "'"];
    $char = $line[$pos];
    $isPythonFString = false;
    
    // Check for Python f-string
    if ($pos > 0 && $line[$pos-1] === 'f' && in_array($char, ['"', "'"])) {
        $isPythonFString = true;
        $pos--; // Include the 'f' prefix
    } elseif (!in_array($char, $quotes)) {
        return null;
    }

    $tokens = [];
    $buffer = '';
    $length = strlen($line);
    $startPos = $pos;
    $escaped = false;

    // First scan through to find the closing quote and collect all content
    for ($i = $pos + ($isPythonFString ? 2 : 1); $i < $length; $i++) {
        $currentChar = $line[$i];
        
        if ($currentChar === '\\') {
            $buffer .= $currentChar;
            $escaped = !$escaped;
            continue;
        }
        
        if ($currentChar === $char && !$escaped) {
            $buffer .= $currentChar;
            break;
        }
        
        $buffer .= $currentChar;
        $escaped = false;
    }

    // Now process the buffer to find interpolations
    $stringContent = $isPythonFString ? 'f' . $char : $char;
    $currentPos = 0;
    $bufferLength = strlen($buffer);

    while ($currentPos < $bufferLength) {
        // Look for interpolation start
        $interpolationStart = false;
        
        if ($char === '`') {
            // JavaScript template literal
            $dollarPos = strpos($buffer, '$', $currentPos);
            if ($dollarPos !== false && isset($buffer[$dollarPos + 1]) && $buffer[$dollarPos + 1] === '{') {
                $interpolationStart = $dollarPos;
                $startLen = 2; // ${
            }
        } elseif ($isPythonFString) {
            // Python f-string
            $bracePos = strpos($buffer, '{', $currentPos);
            if ($bracePos !== false) {
                $interpolationStart = $bracePos;
                $startLen = 1; // {
            }
        }

        if ($interpolationStart !== false) {
            // Add preceding string content if any
            if ($interpolationStart > $currentPos) {
                $precedingContent = substr($buffer, $currentPos, $interpolationStart - $currentPos);
                if ($precedingContent !== '') {
                    // Don't add f prefix for subsequent string parts in Python f-strings
                    $prefix = ($currentPos === 0 && $isPythonFString) ? 'f' . $char : $char;
                    $tokens[] = ['type' => 'string', 'content' => $prefix . $precedingContent];
                }
            }

            // Find matching closing brace
            $depth = 1;
            $interpolationEnd = $interpolationStart + $startLen;
            
            for ($j = $interpolationEnd; $j < $bufferLength; $j++) {
                if ($buffer[$j] === '{') $depth++;
                if ($buffer[$j] === '}') $depth--;
                
                if ($depth === 0) {
                    $interpolationContent = substr($buffer, $interpolationStart, $j - $interpolationStart + 1);
                    $tokens[] = ['type' => 'variable', 'content' => $interpolationContent];
                    $currentPos = $j + 1;
                    break;
                }
            }
            
            $stringContent = ''; // Reset for next string segment
        } else {
            // No more interpolations, add remaining content
            $remainingContent = substr($buffer, $currentPos);
            if ($remainingContent !== '') {
                $tokens[] = ['type' => 'string', 'content' => $stringContent . $remainingContent];
            }
            break;
        }
    }

    return [
        'tokens' => $tokens,
        'length' => strlen($buffer) + ($isPythonFString ? 2 : 1)
    ];
}

function findNextToken($line, $pos, $langComponents) {
    $char = $line[$pos];

    // Check for Python decorator
    if ($char === '@') {
        $length = strlen($line);
        $decoratorName = '@';
        
        // Collect decorator name until whitespace or parenthesis
        for ($i = $pos + 1; $i < $length; $i++) {
            $nextChar = $line[$i];
            if ($nextChar === ' ' || $nextChar === '(' || $nextChar === "\n") {
                break;
            }
            $decoratorName .= $nextChar;
        }
        
        return ['token' => ['type' => 'decorator', 'content' => $decoratorName]];
    }

    // Check for delimiters (single character)
    if (in_array($char, $langComponents['delimiters'])) {
        return ['token' => ['type' => 'delimiter', 'content' => $char]];
    }

    // Check for operators (single character)
    if (in_array($char, $langComponents['operators'])) {
        return ['token' => ['type' => 'operator', 'content' => $char]];
    }

    // Check for keywords and builtins
    foreach (['keywords', 'builtins'] as $tokenType) {
        foreach ($langComponents[$tokenType] as $word) {
            if (substr($line, $pos, strlen($word)) === $word) {
                // Ensure it's a complete word
                $nextChar = $pos + strlen($word) < strlen($line) ? $line[$pos + strlen($word)] : ' ';
                $prevChar = $pos > 0 ? $line[$pos - 1] : ' ';
                if (isWordBoundary($prevChar) && isWordBoundary($nextChar)) {
                    return ['token' => ['type' => rtrim($tokenType, 's'), 'content' => $word]];
                }
            }
        }
    }

    return null;
}

function isTokenStart($line, $pos, $langComponents) {
    $char = $line[$pos];
    error_log("isTokenStart check - char: '$char', pos: $pos, has units: " . (isset($langComponents['units']) ? 'true' : 'false'));

    // Special handling for CSS
    if (isset($langComponents['units'])) {
        // In CSS, '#' and '.' are part of selectors, not token starts
        return in_array($char, array_merge($langComponents['operators'], $langComponents['delimiters'])) ||
               $char === ' ' || $char === '"' || $char === "'" || $char === '`' ||
               ($pos < strlen($line) - 1 && substr($line, $pos, 2) === '//');
    }

    // For other languages
    return in_array($char, array_merge($langComponents['operators'], $langComponents['delimiters'])) ||
           $char === ' ' || $char === '"' || $char === "'" || $char === '`' ||
           ($pos < strlen($line) - 1 && substr($line, $pos, 2) === '//') ||
           $char === '#' || $char === '@';
}

function isWordBoundary($char) {
    return $char === ' ' || $char === "\t" || $char === "\n" || $char === '(' || 
           $char === ')' || $char === '[' || $char === ']' || $char === '{' || 
           $char === '}' || $char === ',' || $char === ';' || $char === '.';
}

function tokenizeRemainingLine($remainingText, $langComponents) {
    return $remainingText ? tokenizeLine($remainingText) : [];
}

/**
 * Decides how to classify a buffer as keyword, builtin, number, or text.
 */
function processBuffer($buffer, $langComponents) {
    // Check if buffer is a number (integer or float)
    if (preg_match('/^-?\d*\.?\d+$/', $buffer)) {
        return ['type' => 'number', 'content' => $buffer];
    }
    
    // Check for Python keywords
    if (in_array($buffer, $langComponents['keywords'])) {
        return ['type' => 'keyword', 'content' => $buffer];
    }
    
    // Check for built-ins
    if (in_array($buffer, $langComponents['builtins'])) {
        return ['type' => 'builtin', 'content' => $buffer];
    }
    
    // Otherwise treat as plain text
    return ['type' => 'text', 'content' => $buffer];
}

/**
 * Renders a single line (line number + tokens).
 * We read leadingIndent from $lineData so we can clamp it to max 6.
 */
function renderCodeLine($lineData, $tokens, $highlights = []) {
    // The number of leading spaces is already stored in lineData['leadingIndent'].
    $indentLevel = $lineData['leadingIndent'];
    $indentClass = "indent-level-{$indentLevel}";

    $highlightClass = in_array($lineData['number'], $highlights) ? ' highlight' : '';
    
    $lineHtml = "<div class='code-line {$indentClass}{$highlightClass}'>";
    $lineHtml .= "<span class='line-number'>{$lineData['number']}</span>";
    
    foreach ($tokens as $token) {
        // Convert HTML special chars, except for & to avoid double-escaping
        $content = htmlspecialchars($token['content'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        $type = $token['type'] === 'code' ? 'text' : $token['type'];
        $lineHtml .= "<span class='{$type}'>{$content}</span>";
    }
    
    $lineHtml .= "</div>";
    return $lineHtml;
}

function getPythonComponents() {
    return [
        'keywords' => [
            'def', 'class', 'return', 'import', 'from', 'if', 'else', 'elif',
            'for', 'while', 'try:', 'except', 'as', 'with', 'in', 'is', 'not',
            'and', 'or', 'True', 'False', 'None', 'self', 'lambda', 'yield',
            'raise', 'break', 'continue', 'pass', 'assert', 'del', 'global',
            'nonlocal', 'async', 'await'
        ],
        'builtins' => [
            'print', 'len', 'range', 'str', 'int', 'float', 'list', 'dict',
            'set', 'tuple', 'bool', 'map', 'filter', 'zip', 'enumerate',
            'super', 'isinstance', 'type', 'min', 'max', 'sum', 'any', 'all'
        ],
        'operators' => [
            '+', '-', '*', '/', '%', '=', '==', '!=', '>', '<', '>=', '<=',
            '+=', '-=', '*=', '/=', '**', '//', '&', '|', '^', '~', '>>', '<<'
        ],
        'delimiters' => ['(', ')', '[', ']', '{', '}', ':', ',', '.', ';']
    ];
}

function getJavaScriptComponents() {
    return [
        'keywords' => ['function','return','if','else','for','forEach', 'while','switch','case','break','continue','let','const','var'],
        'builtins' => ['console','parseInt','parseFloat','Math','Date','JSON','Array','Object','String'],
        'operators' => ['+','-','*','/','%','=', '==','===','!=','!==','>','<','>=','<=','+=','-=','*=','/=','++','--','&&','||','!'],
        'delimiters' => ['(', ')', '[', ']', '{', '}', ':', ',', '.', ';']
    ];
}

function getCssComponents() {
    return [
        'keywords' => [
            'import', 'from', 'to', 'through', '@media', '@keyframes', '@font-face',
            '@import', '@charset', '@layer', '@container', '@supports',
            'hover', 'active', 'focus', 'visited', 'first-child', 'last-child',
            'nth-child', 'before', 'after'
        ],
        'builtins' => [
            'rgb', 'rgba', 'hsl', 'hsla', 'url', 'var',
            'calc', 'min', 'max', 'clamp', 'repeat',
            'linear-gradient', 'radial-gradient'
        ],
        'operators' => [
            ':', ';', '!', '>', '+', '~', '*', '/', '='
        ],
        'delimiters' => [
            '{', '}', '(', ')', '[', ']', ',', '@'
        ],
        'units' => [
            'px', 'em', 'rem', 'vh', 'vw', '%', 'fr', 'deg'
        ]
    ];
}

// Add this function to handle CSS-specific token types
function processCssToken($token, $pos, $line) {
    error_log("processCssToken - token: '$token', pos: $pos, line: '$line'");
    
    // Handle CSS variables (between -- and : or ))
    if (str_starts_with($token, '--')) {
        $endPos = strpos($token, ':');
        if ($endPos !== false) {
            $varName = substr($token, 0, $endPos);
            error_log("Found CSS variable: $varName");
            return ['type' => 'cssvariable', 'content' => $varName];
        }
        $endPos = strpos($token, ')');
        if ($endPos !== false) {
            $varName = substr($token, 0, $endPos);
            error_log("Found CSS variable in function: $varName");
            return ['type' => 'cssvariable', 'content' => $varName];
        }
        // If the entire token starts with -- and has no : or ), it's a complete variable
        error_log("Found complete CSS variable: $token");
        return ['type' => 'cssvariable', 'content' => $token];
    }
    
    // Handle class selectors
    if ($token[0] === '.') {
        error_log("Found class selector: $token");
        return ['type' => 'class-name', 'content' => $token];
    }
    
    // Handle ID selectors
    if ($token[0] === '#') {
        error_log("Found ID selector: $token");
        return ['type' => 'id', 'content' => $token];
    }
    
    // Handle property names - Check if previous character is '{' or ';'
    if ($pos > 0) {
        $prevChar = trim($line[$pos-1]); // Remove any whitespace
        if ($prevChar === '{' || $prevChar === ';') {
            error_log("Found CSS property: $token");
            return ['type' => 'property', 'content' => $token];
        }
    }
    
    // Handle pseudo-classes/elements
    if (strpos($token, ':') === 0) {
        error_log("Found pseudo-class/element: $token");
        return ['type' => 'pseudo', 'content' => $token];
    }
    
    // Handle @-rules
    if ($token[0] === '@') {
        error_log("Found at-rule: $token");
        return ['type' => 'at-rule', 'content' => $token];
    }
    
    // Handle CSS functions
    if (preg_match('/^(var|calc|rgb|rgba|hsl|hsla|url|min|max|clamp)\s*\(/', $token)) {
        error_log("Found CSS function: $token");
        return ['type' => 'css-function', 'content' => $token];
    }
    
    // Handle values with units
    if (preg_match('/^-?\d*\.?\d+([a-z]{1,4}|%)$/', $token)) {
        error_log("Found value with unit: $token");
        $matches = [];
        preg_match('/^(-?\d*\.?\d+)([a-z]{1,4}|%)$/', $token, $matches);
        return [
            'type' => 'property-value',
            'content' => $matches[1],
            'unit' => ['type' => 'property-unit', 'content' => $matches[2]]
        ];
    }
    
    error_log("No CSS token match found for: $token");
    return null;
} 