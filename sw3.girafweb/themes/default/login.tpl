Function call test.
${FUNC|CLASS:METHOD}
Variable declaration.
${VDEC|NAME:VALUE}
Advanced Variable declaration.
${VDEC|NAME:${FUNC|CLASS:METHOD}}
Loop test.
${LOOP|VAR:START}
${LOOP|VAR:END}
Loop with variable reference.
${LOOP|${VREF|NAME}:START}
Conditional
${ENDLOOP}
${IF|TRUE==TRUE}
This is visible if true
${ELSE}
This is if true is false.
${ENDIF}
