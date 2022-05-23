/**
 * Created by josel on 8/2/2017.
 */
//$(function() {

//esconder paneles desde el principio
/*
 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 */


/* $('#tipodepropiedad').change(function(){
 if($('#tipodepropiedad').val() == 'Casa habitación')
 {
 $('#casa').show(500);
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 else if($('#tipodepropiedad').val() == 'Edificio habitacional')
 {

 $('#casa').hide();
 $('#edificiohabitacional').show();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 else if($('#tipodepropiedad').val() == 'Local comercial')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').show();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 else if($('#tipodepropiedad').val() == 'Edificio comercial')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').show();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 else if($('#tipodepropiedad').val() == 'Plaza comercial')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').show();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 else if($('#tipodepropiedad').val() == 'Nave industrial')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').show();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 else if($('#tipodepropiedad').val() == 'Parque industrial')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').show();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 else if($('#tipodepropiedad').val() == 'Lote urbano')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').show();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }

 else if($('#tipodepropiedad').val() == 'Lote comercial')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').show();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }

 else if($('#tipodepropiedad').val() == 'Lote industrial')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').show();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }

 else if($('#tipodepropiedad').val() == 'Lote agricola')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').show();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }

 else if($('#tipodepropiedad').val() == 'Lote turistico')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').show();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }

 else if($('#tipodepropiedad').val() == 'Motel')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').show();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }

 else if($('#tipodepropiedad').val() == 'Hotel')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').show();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }


 else if($('#tipodepropiedad').val() == 'Otro')
 {

 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').show();
 }


 else
 {
 $('#casa').hide();
 $('#edificiohabitacional').hide();
 $('#localcomercial').hide();
 $('#edificiocomercial').hide();

 $('#plazacomercial').hide();
 $('#naveindustrial').hide();
 $('#parqueindustrial').hide();
 $('#loteurbano').hide();
 $('#lotecomercial').hide();
 $('#loteindustrial').hide();
 $('#loteagricola').hide();

 $('#loteturistico').hide();
 $('#motel').hide();
 $('#hotel1').hide();
 $('#hotel2').hide();
 $('#hotel3').hide();
 $('#hotel4').hide();
 $('#hotel5').hide();
 $('#hotelgt').hide();
 $('#otro').hide();
 }
 });
 });
 */