function formFunction(select)
{
    var element = document.getElementById('form1');
    var button = document.getElementById('submitForm');

    element.action = element.action.replace('acao', select.value);
    element.action = element.action.replace('save', select.value);
    element.action = element.action.replace('consulta', select.value);

    switch(select.value)
    {
        case "save":
            button.value = "Registrar";
            break;
        case "consulta":
            button.value = "Consultar";
            break;
        
        default:
            break;
    }
}