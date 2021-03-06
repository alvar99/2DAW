const dniChars = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E'];
const minNumber = 10000000;
const maxNumber = 99999999;
let dnisValidForChar = [];

function start() {
    inputChar = getInputChar();
    addValidDNINumbers(inputChar);
    printValidDNINumbers(200);
}

function getInputChar() {
    inputChar = prompt("Input a Character [A-Z]");
    return inputChar.toUpperCase();
}

function addValidDNINumbers() {
    for (let number = minNumber; number <= 99999999; number++) {
        if (inputChar == dniChars[number%23]) {
            dnisValidForChar.push(number);
        }
    }
}

function printValidDNINumbers(limit) {
    document.write('Exist ' + dnisValidForChar.length + ' DNI <br>');
    for (let dniIndex = 0; dniIndex < limit; dniIndex++) {
        document.write((dniIndex+1) + ' - ' + dnisValidForChar[dniIndex] + '<br>');
    }
}
