let operator = null;
const digRegex = /^\d*(\.\d+)?$/;

function calculate(){
    const output = document.getElementById("out");
    let firstBox = false;
    let secondBox = false;
    let outputText = null;
    let numone = null;
    let numtwo = null;
    const valone = document.getElementById("n1").value;
    if(digRegex.test(valone)){
        console.log(digRegex.test(valone));
        numone = parseFloat(valone);
        firstBox = true;
    } else {
        firstBox = false;
    }
    const valtwo = document.getElementById("n2").value;
    if(digRegex.test(valtwo)){
        console.log(digRegex.test(valtwo));
        numtwo = parseFloat(valtwo);
        secondBox = true;
    } else {
        secondBox = false;
    }

    if(firstBox && secondBox){
        switch (operator) {
            case "plus":
                outputText = numone+numtwo;
                break;
            case "minus":
                outputText = numone-numtwo;
                break;
            case "mult":
                outputText = numone*numtwo;
                break;
            case "div":
                outputText = numone/numtwo;
                break;
            default:
                outputText = "no sign chosen"
                break;
        }
    } else {
        outputText = "fix inputs";
    }

    output.textContent = outputText;
}

document.getElementById("n1").addEventListener("input", function(){
    calculate();
});
document.getElementById("n2").addEventListener("input", function(){
    calculate();
});
const radios = document.getElementsByName("math");
document.getElementById("plus").addEventListener("click", function(){
    operator = "plus";
    calculate();
});
document.getElementById("minus").addEventListener("click", function(){
    operator = "minus";
    calculate();
});
document.getElementById("mult").addEventListener("click", function(){
    operator = "mult";
    calculate();
});
document.getElementById("div").addEventListener("click", function(){
    operator = "div";
    calculate();
});