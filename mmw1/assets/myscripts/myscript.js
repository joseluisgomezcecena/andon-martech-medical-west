/**
 * Created by josel on 7/25/2017.
 */
$(function() {
    //casa esconder opciones
    $('#m2delantero').hide();
    $('#m2trasero').hide();
    $('#banosexteriores').hide();
    $('#iguales').hide();

    $('#localbathsnum').hide();
    $('#elocalbathsnum').hide();
    $('#pelocalbathsnum').hide();


    $('#em2delantero').hide();
    $('#em2trasero').hide();
    $('#ebanosexteriores').hide();

    $('#localesiguales').hide();
    $('#plocalesiguales').hide();


    //nave industrial
    $('#niarrendatario').hide();
    $('#nirenta').hide();

    $('#nioficinas').hide();
    $('#nirentaoficinas').hide();

    //parque industrial
    $('#piarrendatario').hide();
    $('#pirenta').hide();

    $('#pioficinas').hide();
    $('#pirentaoficinas').hide();

    //lote urbano
    $('#propiedadlote').hide();
    //lote comercial
    $('#propiedadlotec').hide();
    //lote industrial
    $('#propiedadlotein').hide();
    //lote agricola
    $('#propiedadlotea').hide();
    //lote turistico
    $('#propiedadlotet').hide();


    $('#delanteroop').change(function(){
        if($('#delanteroop').val() == 'si')
        {
            $('#m2delantero').show(300);
        }
        else
        {
            $('#m2delantero').hide(300);
        }
    });


    $('#traseroop').change(function(){
        if($('#traseroop').val() == 'si')
        {
            $('#m2trasero').show(300);
        }
        else
        {
            $('#m2trasero').hide(300);
        }
    });

    $('#banosexterioresop').change(function(){
        if($('#banosexterioresop').val() == 'si')
        {
            $('#banosexteriores').show(300);
        }
        else
        {
            $('#banosexteriores').hide(300);
        }
    });

    //edificio habitacion//

    $('#edelanteroop').change(function(){
        if($('#edelanteroop').val() == 'si')
        {
            $('#em2delantero').show(300);
        }
        else
        {
            $('#em2delantero').hide(300);
        }
    });


    $('#etraseroop').change(function(){
        if($('#etraseroop').val() == 'si')
        {
            $('#em2trasero').show(300);
        }
        else
        {
            $('#em2trasero').hide(300);
        }
    });

    $('#ebanosexterioresop').change(function(){
        if($('#ebanosexterioresop').val() == 'si')
        {
            $('#ebanosexteriores').show(300);
        }
        else
        {
            $('#ebanosexteriores').hide(300);
        }
    });




    $('#viviendasigualesop').change(function(){
        if($('#viviendasigualesop').val() == 'si')
        {
            $('#iguales').show(300);
        }
        else
        {
            $('#iguales').hide(300);
        }
    });

    //local comercial

    $('#localbathsop').change(function(){
        if($('#localbathsop').val() == 'si')
        {
            $('#localbathsnum').show(300);
        }
        else
        {
            $('#localbathsnum').hide(300);
        }
    });

    //edificio comercial

    $('#elocalbathsop').change(function(){
        if($('#elocalbathsop').val() == 'si')
        {
            $('#elocalbathsnum').show(300);
        }
        else
        {
            $('#elocalbathsnum').hide(300);
        }
    });

    $('#localigualop').change(function(){
        if($('#localigualop').val() == 'si')
        {
            $('#localesiguales').show(300);
        }
        else
        {
            $('#localesiguales').hide(300);
        }
    });

    //plaza comercial
    $('#pelocalbathsop').change(function(){
        if($('#pelocalbathsop').val() == 'si')
        {
            $('#pelocalbathsnum').show(300);
        }
        else
        {
            $('#pelocalbathsnum').hide(300);
        }
    });

    $('#plocaligualop').change(function(){
        if($('#plocaligualop').val() == 'si')
        {
            $('#plocalesiguales').show(300);
        }
        else
        {
            $('#plocalesiguales').hide(300);
        }
    });

    //nave industrial
    $('#nitransaccionop').change(function(){
        if($('#nitransaccionop').val() == 'Renta con arrendatario')
        {
            $('#niarrendatario').show(300);
        }
        else
        {
            $('#niarrendatario').hide(300);
        }
    });

    $('#nitransaccionop').change(function(){
        if($('#nitransaccionop').val() == 'Renta')
        {
            $('#nirenta').show(300);
            $('#nirentaoficinas').show(300);
        }
        else
        {
            $('#nirenta').hide(300);
            $('#nirentaoficinas').hide(300);
        }
    });

    $('#nioficinasop').change(function(){
        if($('#nioficinasop').val() == 'si')
        {
            $('#nioficinas').show(300);
        }
        else
        {
            $('#nioficinas').hide(300);
        }
    });

    //parque industrial


    $('#pitransaccionop').change(function(){
        if($('#pitransaccionop').val() == 'Renta con arrendatario')
        {
            $('#piarrendatario').show(300);
        }
        else
        {
            $('#piarrendatario').hide(300);
        }
    });

    $('#pitransaccionop').change(function(){
        if($('#pitransaccionop').val() == 'Renta')
        {
            $('#pirenta').show(300);
            $('#pirentaoficinas').show(300);
        }
        else
        {
            $('#pirenta').hide(300);
            $('#pirentaoficinas').hide(300);
        }
    });

    $('#pioficinasop').change(function(){
        if($('#pioficinasop').val() == 'si')
        {
            $('#pioficinas').show(300);
        }
        else
        {
            $('#pioficinas').hide(300);
        }
    });

    //lote urbano
    $('#lupropiedadop').change(function(){
        if($('#lupropiedadop').val() == 'si')
        {
            $('#propiedadlote').show(300);
        }
        else
        {
            $('#propiedadlote').hide(300);
        }
    });

    //lote comercial
    $('#lcpropiedadop').change(function(){
        if($('#lcpropiedadop').val() == 'si')
        {
            $('#propiedadlotec').show(300);
        }
        else
        {
            $('#propiedadlotec').hide(300);
        }
    });

    //lote industrial
    $('#linpropiedadop').change(function(){
        if($('#linpropiedadop').val() == 'si')
        {
            $('#propiedadlotein').show(300);
        }
        else
        {
            $('#propiedadlotein').hide(300);
        }
    });
    //lote agricola
    $('#lapropiedadop').change(function(){
        if($('#lapropiedadop').val() == 'si')
        {
            $('#propiedadlotea').show(300);
        }
        else
        {
            $('#propiedadlotea').hide(300);
        }
    });

    //lote turistico
    $('#ltpropiedadop').change(function(){
        if($('#ltpropiedadop').val() == 'si')
        {
            $('#propiedadlotet').show(300);
        }
        else
        {
            $('#propiedadlotet').hide(300);
        }
    });


    //atencion al cliente

    $('#ofertaop').change(function(){
        if($('#ofertaop').val() == 'si')
        {
            $('#ofertacant').show(300);
        }
        else
        {
            $('#ofertacant').hide(300);
        }
    });



});