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
function renderCodeBlocksSection($section) {
    $output = "<div class='code-blocks-content'>";

    foreach ($section['blocks'] as $block) {
        $output .= "<div class='code-block-container'>";
        $output .= "<div class='code-block-header'>";
        $output .= "<span class='code-block-title'>" . strtolower($block['language']) . "</span>";
        $output .= "<button class='copy-button' onclick='copyCode(this)'>";
        $output .= "<ion-icon name='copy-outline'></ion-icon>";
        $output .= "<span class='button-text'>Copy</span>";
        $output .= "</button>";
        $output .= "</div>";
        
        $output .= "<div class='code-block'>";
        $output .= renderCodeBlock($block['code'], $block['language'], $block['highlights'] ?? []);
        $output .= "</div>";
        $output .= "</div>";
    }

    $output .= "</div>";
    return $output;
}

/**
 * Splits code into lines for further tokenization.
 * We also store the full line so we can measure leading indentation.
 */
function splitIntoCodeLines($codeString) {
    // Normalize Windows line endings
    $codeString = str_replace("\r\n", "\n", $codeString);
    // Split but keep the newline character
    $lines = preg_split('/(?<=\n)/', $codeString);
    
    // Remove last empty element if exists
    if (end($lines) === '') {
        array_pop($lines);
    }
    
    $processedLines = [];
    
    foreach ($lines as $index => $line) {
        $processedLines[] = [
            'number' => $index + 1,
            'content' => $line,  // Now includes \n at the end of each line
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
 * Main tokenizer function
 */
function tokenizeLine($line, $language = 'python') {
    switch ($language) {
        case 'javascript':
            $tokens = tokenizeJavaScript($line);
            break;
        case 'css':
            $tokens = tokenizeCSS($line);
            break;
        case 'python':
        default:
            $tokens = tokenizePython($line);
    }
    return $tokens;
}

/**
 * Python-specific tokenizer
 */
function tokenizePython($line) {
    static $multilineState = null;  // Keep state at tokenizer level
    
    $tokens = [];
    $pos = 0;
    $lineLength = strlen($line);
    
    while ($pos < $lineLength) {
        // Safety check for string access
        if ($pos < 0 || $pos >= $lineLength) {
            break;
        }

        // Try each symbol pair configuration
        $matched = false;
        foreach (getSymbolPairsConfig() as $config) {
            $result = processInBetweenSymbols($line, $pos, $config, $multilineState, 'python');
            if ($result && isset($result['tokens'])) {
                $tokens = array_merge($tokens, $result['tokens']);
                $pos = $result['newPos'];
                
                // Always update multiline state from result
                $multilineState = $result['multilineState'];
                
                $matched = true;
                break;
            }
        }

        if ($matched) {
            continue;
        }

        // Check for Python keywords and builtins
        $tokenCheck = checkPythonToken($line, $pos);
        if ($tokenCheck) {
            $tokens[] = $tokenCheck['token'];
            $pos += strlen($tokenCheck['token']['content']);
            continue;
        }

        // Safe character access
        if ($pos >= 0 && $pos < $lineLength) {
            $tokens[] = ['type' => 'text', 'content' => $line[$pos]];
        }
        $pos++;
    }
    
    return $tokens;
}

/**
 * Process Python string literals, including f-strings
 */
function processPythonString($text, $isFString = false) {
    if (empty($text)) {
        return ['tokens' => [], 'length' => 0];
    }

    $textLength = strlen($text);
    
    // Safety check for accessing first character
    if ($textLength === 0) {
        return ['tokens' => [], 'length' => 0];
    }

    // Safety check for f-string
    if ($isFString && $textLength < 2) {
        return ['tokens' => [], 'length' => 0];
    }

    $quote = $isFString ? $text[1] : $text[0];
    $startPos = $isFString ? 1 : 0;  // Skip 'f' if it's an f-string
    $tokens = [];
    $buffer = '';
    $escaped = false;
    
    // Add opening quote (don't include 'f' - it's handled by the main tokenizer)
    $tokens[] = ['type' => 'string', 'content' => $quote];
    
    for ($i = $startPos + 1; $i < $textLength; $i++) {
        // Safety check for character access
        if ($i < 0 || $i >= $textLength) {
            break;
        }

        $char = $text[$i];
        
        if ($escaped) {
            $buffer .= $char;
            $escaped = false;
            continue;
        }
        
        if ($char === '\\') {
            $buffer .= $char;
            $escaped = true;
            continue;
        }
        
        if ($char === $quote && !$escaped) {
            // Found end of string
            if ($isFString && $buffer !== '') {
                // Only process interpolation for f-strings
                $interpolated = processPythonInterpolation($buffer);
                $tokens = array_merge($tokens, $interpolated);
            } else if ($buffer !== '') {
                // Regular string content
                $tokens[] = ['type' => 'string', 'content' => $buffer];
            }
            // Add closing quote
            $tokens[] = ['type' => 'string', 'content' => $quote];
            return [
                'tokens' => $tokens,
                'length' => $i + 1
            ];
        }
        
        $buffer .= $char;
    }
    
    // If we get here, string wasn't closed
    if ($buffer !== '') {
        $tokens[] = ['type' => 'string', 'content' => $buffer];
    }
    return [
        'tokens' => $tokens,
        'length' => $textLength
    ];
}

/**
 * Process Python f-string interpolation
 */
function processPythonInterpolation($text) {
    if (empty($text)) {
        return [];
    }

    $textLength = strlen($text);
    $tokens = [];
    $currentPos = 0;
    $buffer = '';
    
    while ($currentPos < $textLength) {
        // Safety check for character access
        if ($currentPos < 0 || $currentPos >= $textLength) {
            break;
        }

        $char = $text[$currentPos];
        
        if ($char === '{') {
            // Add preceding string content if any
            if ($buffer !== '') {
                $tokens[] = ['type' => 'string', 'content' => $buffer];
                $buffer = '';
            }
            
            // Find matching closing brace
            $depth = 1;
            $interpolationStart = $currentPos;
            $currentPos++;
            $interpolationBuffer = '{';  // Include opening brace
            
            while ($currentPos < $textLength && $depth > 0) {
                // Safety check for character access
                if ($currentPos < 0 || $currentPos >= $textLength) {
                    break;
                }

                $currentChar = $text[$currentPos];
                $interpolationBuffer .= $currentChar;  // Add all chars including braces
                
                if ($currentChar === '{') {
                    $depth++;
                } else if ($currentChar === '}') {
                    $depth--;
                }
                $currentPos++;
            }
            
            // Only process if we found a complete interpolation
            if ($depth === 0) {
                // Process the interpolated content recursively (including braces)
                $interpolatedTokens = tokenizePython($interpolationBuffer);
                $tokens = array_merge($tokens, $interpolatedTokens);
            } else {
                // If interpolation wasn't closed, treat as regular string
                $tokens[] = ['type' => 'string', 'content' => $interpolationBuffer];
            }
            continue;
        }
        
        $buffer .= $char;
        $currentPos++;
    }
    
    // Add any remaining string content
    if ($buffer !== '') {
        $tokens[] = ['type' => 'string', 'content' => $buffer];
    }
    
    return $tokens;
}

/**
 * Check for Python keywords and builtins
 */
function checkPythonToken($line, $pos) {
    // Safety check for position
    if ($pos < 0 || $pos >= strlen($line)) {
        return null;
    }

    $pythonComponents = [
        'keywords' => [
            'def', 'class', 'return', 'import', 'from', 'if', 'else', 'elif',
            'for', 'while', 'try', 'except', 'as', 'with', 'in', 'is', 'not',
            'and', 'or', 'True', 'False', 'None', 'self', 'lambda', 'yield',
            'raise', 'break', 'continue', 'pass', 'assert', 'del', 'global',
            'nonlocal', 'async', 'await'
        ],
        'builtins' => [
            'print', 'len', 'range', 'str', 'int', 'float', 'list', 'dict',
            'set', 'tuple', 'bool', 'map', 'filter', 'zip', 'enumerate',
            'super', 'isinstance', 'type', 'min', 'max', 'sum', 'any', 'all'
        ]
    ];

    // Sort keywords by length (longest first)
    $keywords = $pythonComponents['keywords'];
    usort($keywords, function($a, $b) {
        return strlen($b) - strlen($a);
    });

    // Check for keywords
    foreach ($keywords as $keyword) {
        $len = strlen($keyword);
        // Safety check for substring
        if ($pos + $len > strlen($line)) {
            continue;
        }
        
        $substr = substr($line, $pos, $len);
        
        if ($substr === $keyword) {
            // Check if it's a complete word by looking at boundaries
            $nextChar = $pos + $len < strlen($line) ? $line[$pos + $len] : ' ';
            $prevChar = ($pos > 0 && $pos < strlen($line)) ? $line[$pos - 1] : ' ';
            
            if (isWordBoundary($prevChar) && (isWordBoundary($nextChar) || $nextChar === ':')) {
                return ['token' => ['type' => 'keyword', 'content' => $keyword]];
            }
        }
    }

    // Sort builtins by length (longest first)
    $builtins = $pythonComponents['builtins'];
    usort($builtins, function($a, $b) {
        return strlen($b) - strlen($a);
    });

    // Check for builtins
    foreach ($builtins as $builtin) {
        $len = strlen($builtin);
        // Safety check for substring
        if ($pos + $len > strlen($line)) {
            continue;
        }
        
        $substr = substr($line, $pos, $len);
        
        if ($substr === $builtin) {
            $nextChar = $pos + $len < strlen($line) ? $line[$pos + $len] : ' ';
            $prevChar = ($pos > 0 && $pos < strlen($line)) ? $line[$pos - 1] : ' ';
            
            if (isWordBoundary($prevChar) && (isWordBoundary($nextChar) || $nextChar === ':')) {
                return ['token' => ['type' => 'builtin', 'content' => $builtin]];
            }
        }
    }

    return null;
}

/**
 * Helper function to check word boundaries
 */
function isWordBoundary($char) {
    return $char === ' ' || $char === "\t" || $char === "\n" || $char === '(' || 
           $char === ')' || $char === '[' || $char === ']' || $char === '{' || 
           $char === '}' || $char === ',' || $char === ';' || $char === '.' ||
           $char === ':';  // Added colon as word boundary
}

/**
 * Renders a single line (line number + tokens)
 */
function renderCodeLine($lineData, $tokens, $highlights = []) {
    $indentLevel = $lineData['leadingIndent'];
    $indentClass = "indent-level-{$indentLevel}";
    $highlightClass = in_array($lineData['number'], $highlights) ? ' highlight' : '';
    
    $lineHtml = "<div class='code-line {$indentClass}{$highlightClass}'>";
    $lineHtml .= "<span class='line-number'>{$lineData['number']}</span>";
    
    // For empty lines, add a non-breaking space to maintain height
    if (empty($tokens)) {
        $lineHtml .= "<span class='text'>&nbsp;</span>";
    } else {
        foreach ($tokens as $token) {
            if (!is_array($token) || !isset($token['type']) || !isset($token['content'])) {
                continue;
            }
            
            $content = htmlspecialchars($token['content'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
            $type = $token['type'] === 'code' ? 'text' : $token['type'];
            $lineHtml .= "<span class='{$type}'>{$content}</span>";
        }
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
    // Handle CSS variables (between -- and : or ))
    if (str_starts_with($token, '--')) {
        $endPos = strpos($token, ':');
        if ($endPos !== false) {
            $varName = substr($token, 0, $endPos);
            return ['type' => 'cssvariable', 'content' => $varName];
        }
        $endPos = strpos($token, ')');
        if ($endPos !== false) {
            $varName = substr($token, 0, $endPos);
            return ['type' => 'cssvariable', 'content' => $varName];
        }
        // If the entire token starts with -- and has no : or ), it's a complete variable
        return ['type' => 'cssvariable', 'content' => $token];
    }
    
    // Handle class selectors
    if ($token[0] === '.') {
        return ['type' => 'class-name', 'content' => $token];
    }
    
    // Handle ID selectors
    if ($token[0] === '#') {
        return ['type' => 'id', 'content' => $token];
    }
    
    // Handle property names - Check if previous character is '{' or ';'
    if ($pos > 0) {
        $prevChar = trim($line[$pos-1]); // Remove any whitespace
        if ($prevChar === '{' || $prevChar === ';') {
            return ['type' => 'property', 'content' => $token];
        }
    }
    
    // Handle pseudo-classes/elements
    if (strpos($token, ':') === 0) {
        return ['type' => 'pseudo', 'content' => $token];
    }
    
    // Handle @-rules
    if ($token[0] === '@') {
        return ['type' => 'at-rule', 'content' => $token];
    }
    
    // Handle CSS functions
    if (preg_match('/^(var|calc|rgb|rgba|hsl|hsla|url|min|max|clamp)\s*\(/', $token)) {
        return ['type' => 'css-function', 'content' => $token];
    }
    
    // Handle values with units
    if (preg_match('/^-?\d*\.?\d+([a-z]{1,4}|%)$/', $token)) {
        $matches = [];
        preg_match('/^(-?\d*\.?\d+)([a-z]{1,4}|%)$/', $token, $matches);
        return [
            'type' => 'property-value',
            'content' => $matches[1],
            'unit' => ['type' => 'property-unit', 'content' => $matches[2]]
        ];
    }
    
    return null;
}

/**
 * Checks if current position starts a multiline token
 */
function checkMultilineStart($line, $pos, $language) {
    $multilineDelimiters = [
        'python' => [
            ['start' => '"""', 'end' => '"""', 'type' => 'comment'],
            ['start' => "'''", 'end' => "'''", 'type' => 'comment']
        ],
        'javascript' => [
            ['start' => '/*', 'end' => '*/', 'type' => 'comment']
        ],
        'css' => [
            ['start' => '/*', 'end' => '*/', 'type' => 'css-comment']
        ]
    ];

    $delimiters = $multilineDelimiters[$language] ?? $multilineDelimiters['python'];
    
    foreach ($delimiters as $delimiter) {
        if (substr($line, $pos, strlen($delimiter['start'])) === $delimiter['start']) {
            $endPos = strpos($line, $delimiter['end'], $pos + strlen($delimiter['start']));
            return [
                'found' => true,
                'delimiter' => $delimiter,
                'closedInLine' => $endPos !== false,
                'content' => $endPos !== false ? 
                    substr($line, $pos, $endPos + strlen($delimiter['end']) - $pos) :
                    substr($line, $pos)
            ];
        }
    }
    
    return ['found' => false];
}

/**
 * Processes interpolation within string literals
 */
function processInterpolation($text, $startPos, $startDelim, $endDelim, $processContent = true) {
    $tokens = [];
    $pos = $startPos;
    $length = strlen($text);
    $depth = 1;
    $buffer = $startDelim;  // Start with opening delimiter
    $pos++;  // Move past start delimiter
    
    while ($pos < $length && $depth > 0) {
        $char = $text[$pos];
        
        if ($char === $startDelim) {
            $depth++;
            $buffer .= $char;
        } else if ($char === $endDelim) {
            $depth--;
            if ($depth > 0) {
                $buffer .= $char;
            }
        } else {
            $buffer .= $char;
        }
        
        $pos++;
    }
    
    if ($depth === 0) {
        // Complete interpolation found
        if ($processContent) {
            // Process the content between delimiters
            $content = substr($buffer, 1);  // Remove opening delimiter
            $innerTokens = tokenizePython($content);
            
            // Add delimiters and inner content
            $tokens[] = ['type' => 'delimiter', 'content' => $startDelim];
            $tokens = array_merge($tokens, $innerTokens);
            $tokens[] = ['type' => 'delimiter', 'content' => $endDelim];
        } else {
            // Keep as single token
            $tokens[] = ['type' => 'string', 'content' => $buffer . $endDelim];
        }
        
        return [
            'tokens' => $tokens,
            'newPos' => $pos
        ];
    }
    
    // Incomplete interpolation - treat as regular string
    return [
        'tokens' => [['type' => 'string', 'content' => $buffer]],
        'newPos' => $pos
    ];
}

/**
 * Process Python decorators
 */
function processDecorator($text) {
    if (empty($text) || $text[0] !== '@') {
        return null;
    }

    $pos = 1;  // Start after @
    $buffer = '@';  // Start with @ symbol
    $length = strlen($text);

    // Collect decorator name until whitespace or (
    while ($pos < $length) {
        $char = $text[$pos];
        
        if ($char === ' ' || $char === "\t" || $char === "\n" || $char === '(') {
            break;
        }
        
        $buffer .= $char;
        $pos++;
    }

    return [
        'tokens' => [
            ['type' => 'decorator', 'content' => $buffer]
        ],
        'length' => $pos
    ];
}

/**
 * Configuration for in-between symbol pairs and their interpolation rules
 */
function getSymbolPairsConfig() {
    return [
        // Triple quotes - multiline docstrings/comments
        [
            'start' => '"""',
            'end' => '"""',
            'type' => 'comment',
            'interpolation' => null
        ],
        [
            'start' => "'''",
            'end' => "'''",
            'type' => 'comment',
            'interpolation' => null
        ],
        
        // F-strings with double quotes
        [
            'start' => 'f"',
            'end' => '"',
            'type' => 'string',
            'interpolation' => [
                'pairs' => [
                    ['start' => '{', 'end' => '}']
                ]
            ]
        ],
        
        // F-strings with single quotes
        [
            'start' => "f'",
            'end' => "'",
            'type' => 'string',
            'interpolation' => [
                'pairs' => [
                    ['start' => '{', 'end' => '}']
                ]
            ]
        ],
        
        // Regular double quoted strings
        [
            'start' => '"',
            'end' => '"',
            'type' => 'string',
            'interpolation' => null
        ],
        
        // Regular single quoted strings
        [
            'start' => "'",
            'end' => "'",
            'type' => 'string',
            'interpolation' => null
        ],
        
        // Decorators
        [
            'start' => '@',
            'end' => ' ',
            'type' => 'decorator',
            'interpolation' => null
        ]
    ];
}

/**
 * Generic in-between symbols token processor
 */
function processInBetweenSymbols($line, $pos, $config, $currentState, $language = 'python') {
    $lineLength = strlen($line);
    
    // Validate required config fields
    if (!isset($config['start']) || !isset($config['end']) || !isset($config['type'])) {
        return null;
    }

    // 1. Handle multiline state if exists
    if ($currentState) {
        // Find end symbol in current line
        $endPos = strpos($line, $currentState['end'], $pos);
        
        if ($endPos !== false) {
            // Found end of multiline - process up to and including end symbol
            $content = substr($line, $pos, $endPos + strlen($currentState['end']) - $pos);
            
            // Process any interpolation in the collected content
            if (isset($currentState['interpolation'])) {
                $tokens = processWithInterpolation($content, $currentState['type'], $currentState['interpolation']['pairs'], $language);
            } else {
                $tokens = [['type' => $currentState['type'], 'content' => $content]];
            }
            
            return [
                'tokens' => $tokens,
                'newPos' => $endPos + strlen($currentState['end']),
                'multilineState' => null
            ];
        }
    }

    // 2. Look for start symbol
    if (substr_compare($line, $config['start'], $pos, strlen($config['start'])) !== 0) {
        return null;
    }

    // 3. Found start - collect content until end symbol or EOL
    $buffer = $config['start'];
    $currentPos = $pos + strlen($config['start']);
    
    while ($currentPos < $lineLength) {
        // Look for end symbol
        if (substr_compare($line, $config['end'], $currentPos, strlen($config['end'])) === 0) {
            // Found end on same line
            $buffer .= substr($line, $currentPos, strlen($config['end']));
            
            // Process collected content with any interpolation
            if (isset($config['interpolation'])) {
                $tokens = processWithInterpolation($buffer, $config['type'], $config['interpolation']['pairs'], $language);
            } else {
                $tokens = [['type' => $config['type'], 'content' => $buffer]];
            }
            
            return [
                'tokens' => $tokens,
                'newPos' => $currentPos + strlen($config['end']),
                'multilineState' => null
            ];
        }
        
        $buffer .= $line[$currentPos];
        $currentPos++;
    }

    // 4. Reached EOL without finding end symbol - start multiline state
    return [
        'tokens' => [['type' => $config['type'], 'content' => $buffer]],
        'newPos' => $lineLength,
        'multilineState' => [
            'type' => $config['type'],
            'end' => $config['end'],
            'interpolation' => $config['interpolation']
        ]
    ];
}

/**
 * Process content with interpolation pairs
 */
function processWithInterpolation($text, $type, $interpolationPairs, $language = 'python') {
    $tokens = [];
    $pos = 0;
    $length = strlen($text);
    $buffer = '';

    while ($pos < $length) {
        $foundInterpolation = false;
        
        // Check each interpolation pair
        foreach ($interpolationPairs as $pair) {
            if (substr_compare($text, $pair['start'], $pos, strlen($pair['start'])) === 0) {
                // Found interpolation start
                if ($buffer !== '') {
                    $tokens[] = ['type' => $type, 'content' => $buffer];
                    $buffer = '';
                }

                // Add opening delimiter
                $tokens[] = ['type' => 'delimiter', 'content' => $pair['start']];

                // Collect until matching end symbol
                $interpBuffer = '';
                $interpPos = $pos + strlen($pair['start']);
                $depth = 1;

                while ($interpPos < $length && $depth > 0) {
                    if (substr_compare($text, $pair['start'], $interpPos, strlen($pair['start'])) === 0) {
                        $depth++;
                        $interpBuffer .= substr($text, $interpPos, strlen($pair['start']));
                        $interpPos += strlen($pair['start']);
                    } else if (substr_compare($text, $pair['end'], $interpPos, strlen($pair['end'])) === 0) {
                        $depth--;
                        if ($depth > 0) {
                            $interpBuffer .= substr($text, $interpPos, strlen($pair['end']));
                        }
                        $interpPos += strlen($pair['end']);
                    } else {
                        $interpBuffer .= $text[$interpPos];
                        $interpPos++;
                    }
                }

                if ($depth === 0) {
                    // Recursively tokenize the interpolated content with correct language
                    $innerTokens = tokenizeLine($interpBuffer, $language);
                    $tokens = array_merge($tokens, $innerTokens);
                    
                    // Add closing delimiter
                    $tokens[] = ['type' => 'delimiter', 'content' => $pair['end']];
                    
                    $pos = $interpPos;
                    $foundInterpolation = true;
                    break;
                } else {
                    // No matching end found - treat as regular content
                    $buffer .= $pair['start'] . $interpBuffer;
                }
            }
        }

        if (!$foundInterpolation) {
            $buffer .= $text[$pos];
            $pos++;
        }
    }

    if ($buffer !== '') {
        $tokens[] = ['type' => $type, 'content' => $buffer];
    }

    return $tokens;
}

/**
 * Renders a code block with syntax highlighting
 */
function renderCodeBlock($code, $language, $highlights = []) {
    // Normalize Windows line endings
    $code = str_replace("\r\n", "\n", $code);
    
    // Split into lines and process each
    $codeLines = splitIntoCodeLines($code);
    $output = "<pre><code>";
    
    foreach ($codeLines as $line) {
        // Tokenize the line based on language
        $tokens = tokenizeLine($line['content'], $language);
        
        // Render the line with tokens and any highlights
        $lineHtml = renderCodeLine($line, $tokens, $highlights);
        $output .= $lineHtml;
    }
    
    $output .= "</code></pre>";
    return $output;
}

/**
 * JavaScript-specific tokenizer
 */
function tokenizeJavaScript($line) {
    static $multilineState = null;
    
    $tokens = [];
    $pos = 0;
    $lineLength = strlen($line);
    
    while ($pos < $lineLength) {
        // Safety check for string access
        if ($pos < 0 || $pos >= $lineLength) {
            break;
        }

        // Try each symbol pair configuration
        $matched = false;
        foreach (getJavaScriptSymbolPairsConfig() as $config) {
            $result = processInBetweenSymbols($line, $pos, $config, $multilineState, 'javascript');
            if ($result && isset($result['tokens'])) {
                $tokens = array_merge($tokens, $result['tokens']);
                $pos = $result['newPos'];
                $multilineState = $result['multilineState'];
                $matched = true;
                break;
            }
        }

        if ($matched) {
            continue;
        }

        // Check for JavaScript keywords and builtins
        $tokenCheck = checkJavaScriptToken($line, $pos);
        if ($tokenCheck) {
            $tokens[] = $tokenCheck['token'];
            $pos += strlen($tokenCheck['token']['content']);
            continue;
        }

        // Safe character access
        if ($pos >= 0 && $pos < $lineLength) {
            $tokens[] = ['type' => 'text', 'content' => $line[$pos]];
        }
        $pos++;
    }
    
    return $tokens;
}

/**
 * Check for JavaScript tokens
 */
function checkJavaScriptToken($line, $pos) {
    // Safety check for position
    if ($pos < 0 || $pos >= strlen($line)) {
        return null;
    }

    $jsComponents = [
        'keywords' => [
            'function', 'class', 'return', 'if', 'else', 'for', 'while', 'do',
            'break', 'continue', 'try', 'catch', 'finally', 'throw', 'switch',
            'case', 'default', 'export', 'import', 'from', 'as', 'extends',
            'implements', 'new', 'this', 'super', 'constructor', 'static',
            'get', 'set', 'async', 'await', 'yield', 'const', 'let', 'var',
            'typeof', 'instanceof', 'in', 'of', 'delete', 'void', 'null',
            'undefined', 'true', 'false'
        ],
        'builtins' => [
            'Array', 'Object', 'String', 'Number', 'Boolean', 'Function',
            'Symbol', 'Map', 'Set', 'Promise', 'Date', 'RegExp', 'Error',
            'Math', 'JSON', 'console', 'document', 'window', 'global',
            'parseInt', 'parseFloat', 'isNaN', 'isFinite', 'encodeURI',
            'decodeURI', 'setTimeout', 'setInterval'
        ],
        'operators' => [
            '=>', '...', '++', '--', '**', '&&', '||', '??', '?.', '+=',
            '-=', '*=', '/=', '%=', '**=', '<<=', '>>=', '>>>=', '&=',
            '|=', '^=', '==', '===', '!=', '!==', '>=', '<=', '>>', '<<',
            '>>>', '+', '-', '*', '/', '%', '!', '&', '|', '^', '~',
            '=', '<', '>', '?', ':'
        ],
        'delimiters' => [
            '.', ',', ';', '(', ')', '[', ']', '{', '}', '`'
        ]
    ];

    // Check for numbers (including decimals, hex, binary, octal)
    if (preg_match('/^(?:0[xXbBoO][0-9a-fA-F]+|(?:\d*\.)?\d+(?:[eE][+-]?\d+)?)/', substr($line, $pos), $matches)) {
        return ['token' => ['type' => 'number', 'content' => $matches[0]]];
    }

    // Check for operators
    foreach ($jsComponents['operators'] as $operator) {
        if (substr_compare($line, $operator, $pos, strlen($operator)) === 0) {
            return ['token' => ['type' => 'operator', 'content' => $operator]];
        }
    }

    // Check for delimiters
    foreach ($jsComponents['delimiters'] as $delimiter) {
        if (substr_compare($line, $delimiter, $pos, strlen($delimiter)) === 0) {
            return ['token' => ['type' => 'delimiter', 'content' => $delimiter]];
        }
    }

    // Check for keywords
    foreach ($jsComponents['keywords'] as $keyword) {
        $len = strlen($keyword);
        if ($pos + $len > strlen($line)) {
            continue;
        }
        
        if (substr_compare($line, $keyword, $pos, $len) === 0) {
            $nextChar = $pos + $len < strlen($line) ? $line[$pos + $len] : ' ';
            $prevChar = ($pos > 0 && $pos < strlen($line)) ? $line[$pos - 1] : ' ';
            
            if (isWordBoundary($prevChar) && isWordBoundary($nextChar)) {
                return ['token' => ['type' => 'keyword', 'content' => $keyword]];
            }
        }
    }

    // Check for builtins
    foreach ($jsComponents['builtins'] as $builtin) {
        $len = strlen($builtin);
        if ($pos + $len > strlen($line)) {
            continue;
        }
        
        if (substr_compare($line, $builtin, $pos, $len) === 0) {
            $nextChar = $pos + $len < strlen($line) ? $line[$pos + $len] : ' ';
            $prevChar = ($pos > 0 && $pos < strlen($line)) ? $line[$pos - 1] : ' ';
            
            if (isWordBoundary($prevChar) && isWordBoundary($nextChar)) {
                return ['token' => ['type' => 'builtin', 'content' => $builtin]];
            }
        }
    }

    return null;
}

/**
 * Configuration for JavaScript symbol pairs
 */
function getJavaScriptSymbolPairsConfig() {
    return [
        // Single line comments
        [
            'start' => '//',
            'end' => "  ",
            'type' => 'comment',
            'interpolation' => null
        ],
        // Multi-line comments
        [
            'start' => '/*',
            'end' => '*/',
            'type' => 'comment',
            'interpolation' => null
        ],
        // Template literals with interpolation
        [
            'start' => '`',
            'end' => '`',
            'type' => 'string',
            'interpolation' => [
                'pairs' => [
                    ['start' => '${', 'end' => '}']
                ]
            ]
        ],
        // Regular double quoted strings
        [
            'start' => '"',
            'end' => '"',
            'type' => 'string',
            'interpolation' => null
        ],
        // Regular single quoted strings
        [
            'start' => "'",
            'end' => "'",
            'type' => 'string',
            'interpolation' => null
        ]
    ];
}

/**
 * CSS-specific tokenizer
 */
function tokenizeCSS($line) {
    static $multilineState = null;
    
    $tokens = [];
    $pos = 0;
    $lineLength = strlen($line);
    
    while ($pos < $lineLength) {
        // Safety check for string access
        if ($pos < 0 || $pos >= $lineLength) {
            break;
        }

        // Try each symbol pair configuration
        $matched = false;
        foreach (getCSSSymbolPairsConfig() as $config) {
            $result = processInBetweenSymbols($line, $pos, $config, $multilineState, 'css');
            if ($result && isset($result['tokens'])) {
                $tokens = array_merge($tokens, $result['tokens']);
                $pos = $result['newPos'];
                $multilineState = $result['multilineState'];
                $matched = true;
                break;
            }
        }

        if ($matched) {
            continue;
        }

        // Check for CSS specific tokens
        $tokenCheck = checkCSSToken($line, $pos);
        if ($tokenCheck) {
            $tokens[] = $tokenCheck['token'];
            $pos += strlen($tokenCheck['token']['content']);
            continue;
        }

        // Safe character access
        if ($pos >= 0 && $pos < $lineLength) {
            $tokens[] = ['type' => 'text', 'content' => $line[$pos]];
        }
        $pos++;
    }
    
    return $tokens;
}

/**
 * Check for CSS tokens
 */
function checkCSSToken($line, $pos) {
    // Safety check for position
    if ($pos < 0 || $pos >= strlen($line)) {
        return null;
    }

    $cssComponents = [
        'at-rules' => [
            '@media', '@import', '@keyframes', '@font-face', '@supports',
            '@layer', '@container', '@property', '@charset', '@namespace'
        ],
        'properties' => [
            'color', 'background', 'margin', 'padding', 'border', 'display',
            'position', 'width', 'height', 'font-size', 'font-family',
            'grid-template', 'flex', 'gap', 'transition', 'transform',
            'animation', 'opacity', 'z-index', 'overflow', 'box-shadow'
        ],
        'pseudo' => [
            ':hover', ':focus', ':active', ':visited', ':first-child',
            ':last-child', ':nth-child', ':not', ':is', ':where',
            '::before', '::after', '::first-line', '::selection'
        ],
        'units' => [
            'px', 'em', 'rem', 'vh', 'vw', '%', 'ch', 'ex', 'vmin',
            'vmax', 'cm', 'mm', 'in', 'pt', 'pc', 'fr', 'deg', 'rad',
            'grad', 'turn', 's', 'ms'
        ],
        'functions' => [
            'rgb', 'rgba', 'hsl', 'hsla', 'var', 'calc', 'clamp',
            'min', 'max', 'url', 'linear-gradient', 'radial-gradient'
        ],
        'values' => [
            'inherit', 'initial', 'unset', 'revert', 'auto', 'none',
            'flex', 'grid', 'block', 'inline', 'relative', 'absolute',
            'fixed', 'sticky', 'hidden', 'visible', 'pointer'
        ]
    ];

    // Check for hex colors
    if (preg_match('/^#[0-9a-fA-F]{3,8}/', substr($line, $pos), $matches)) {
        return ['token' => ['type' => 'color', 'content' => $matches[0]]];
    }

    // Check for numbers with units
    if (preg_match('/^-?\d*\.?\d+/', substr($line, $pos), $matches)) {
        $number = $matches[0];
        $len = strlen($number);
        
        // Look for unit after number
        foreach ($cssComponents['units'] as $unit) {
            if (substr_compare($line, $unit, $pos + $len, strlen($unit)) === 0) {
                return ['token' => [
                    'type' => 'number',
                    'content' => $number . $unit
                ]];
            }
        }
        
        // Just a number without unit
        return ['token' => ['type' => 'number', 'content' => $number]];
    }

    // Check for at-rules
    foreach ($cssComponents['at-rules'] as $rule) {
        if (substr_compare($line, $rule, $pos, strlen($rule)) === 0) {
            return ['token' => ['type' => 'at-rule', 'content' => $rule]];
        }
    }

    // Check for properties
    foreach ($cssComponents['properties'] as $prop) {
        if (substr_compare($line, $prop, $pos, strlen($prop)) === 0) {
            $nextChar = $pos + strlen($prop) < strlen($line) ? $line[$pos + strlen($prop)] : ' ';
            if ($nextChar === ':' || $nextChar === ' ') {
                return ['token' => ['type' => 'property', 'content' => $prop]];
            }
        }
    }

    // Check for pseudo-classes/elements
    foreach ($cssComponents['pseudo'] as $pseudo) {
        if (substr_compare($line, $pseudo, $pos, strlen($pseudo)) === 0) {
            return ['token' => ['type' => 'pseudo', 'content' => $pseudo]];
        }
    }

    // Check for functions
    foreach ($cssComponents['functions'] as $func) {
        if (substr_compare($line, $func, $pos, strlen($func)) === 0) {
            $nextChar = $pos + strlen($func) < strlen($line) ? $line[$pos + strlen($func)] : ' ';
            if ($nextChar === '(') {
                return ['token' => ['type' => 'function', 'content' => $func]];
            }
        }
    }

    // Check for values
    foreach ($cssComponents['values'] as $value) {
        if (substr_compare($line, $value, $pos, strlen($value)) === 0) {
            $nextChar = $pos + strlen($value) < strlen($line) ? $line[$pos + strlen($value)] : ' ';
            $prevChar = $pos > 0 ? $line[$pos - 1] : ' ';
            if (isWordBoundary($prevChar) && isWordBoundary($nextChar)) {
                return ['token' => ['type' => 'value', 'content' => $value]];
            }
        }
    }

    // Check for CSS variables
    if (substr_compare($line, '--', $pos, 2) === 0) {
        if (preg_match('/^--[\w-]+/', substr($line, $pos), $matches)) {
            return ['token' => ['type' => 'cssvariable', 'content' => $matches[0]]];
        }
    }

    return null;
}

/**
 * Configuration for CSS symbol pairs
 */
function getCSSSymbolPairsConfig() {
    return [
        // Comments
        [
            'start' => '/*',
            'end' => '*/',
            'type' => 'comment',
            'interpolation' => null
        ],
        // Strings with double quotes
        [
            'start' => '"',
            'end' => '"',
            'type' => 'string',
            'interpolation' => null
        ],
        // Strings with single quotes
        [
            'start' => "'",
            'end' => "'",
            'type' => 'string',
            'interpolation' => null
        ]
    ];
} 