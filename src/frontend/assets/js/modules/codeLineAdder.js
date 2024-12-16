'use strict';

export function initCodeLineAdder() {
    const codeContent = document.getElementById("code-content");
    const lineNumbers = document.querySelector(".code-line-numbers");

    if (!codeContent || !lineNumbers) return;

    const codeLines = codeContent.textContent.trim().split("\n");
    const overshoot = 2;

    lineNumbers.innerHTML = codeLines
        .map((_, i) => `<span>${i + 1}</span>`)
        .concat(Array.from({ length: overshoot }, (_, i) => `<span>${codeLines.length + i + 1}</span>`))
        .join("\n");
}
