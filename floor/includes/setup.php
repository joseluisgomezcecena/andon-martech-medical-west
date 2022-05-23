<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        
          <!-- Modal content-->
        <div class="modal-content">
              <div style="background-color: #337ab7; color:#ffffff;" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Reporte de <b>Setup.</b></h4>
              </div>
            
            <div class="modal-body">
              
              
               
                
                <form method="post" onsubmit="myFunction1()">
                    
                    <div id="demo"></div><!--loading icon-->
                    
                    <div id="disp"><!--desaparece cuando se envia el error --->
                        
                        <div class="form-group">
                            <label>Planta</label><br />
                            <select id="first_level" name="first_level" multiple class="form-control" required>
                            <?php 
                                $query_planta = "SELECT * FROM martech_plantas";
                                $result_planta = mysqli_query($connection, $query_planta);
                                while($row = mysqli_fetch_array($result_planta)):
                                ?>
                                
                                    <option value="<?php  echo $row['id'] ?>"><?php echo $row['nombre'] ?></option>

                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Departamento</label><br />
                            <select id="second_level" name="second_level" multiple class="form-control" required>
                                    
                            </select>
                        </div>


                        <div id="linea_manual" class="form-group">
                            <label>Captura manual de linea/celda</label><br />
                            <input class="form-control" name="linea_manual" >
                        </div>

                        
                        <div id="maquina" class="form-group">
                            
                            <label>Maquina</label><br />
                            <select id="third_level" name="third_level"   class="form-control" required>
                        
                            </select>
                        
                        </div>
                        

                        <div class="form-group">
                            
                            <label>Capturado por</label><br />
                            <select id="capturado" name="capturado"   class="form-control">
                                <?php 
                                $sql = "SELECT user_nombre, user_apellido, user_name, user_id, user_numero FROM users WHERE user_puesto = 'lider' OR user_puesto = 'supervisor' ORDER BY user_puesto ";
                                $run_sql = mysqli_query($connection, $sql);
                                while($row_sql = mysqli_fetch_array($run_sql)):
                                ?>
                                    <option ><?php echo $row_sql['user_nombre']." ".$row_sql['user_apellido']; ?></option>
                                <?php endwhile; ?>
<option>BENJAMIN CHACON</option>
<option>JAIME MORALES</option>
				    <option>MARTIN HUARAQUI</option>
                               




                            </select>
                                    
                        </div>

			




			<div class="form-group">
                            <label>Describe el problema (Obligatorio)</label>
                            <select style="text-transform: uppercase;" class="form-control" id="descripcion" name="descripcion" required>
                                <option value="">Seleccione</option>
                                <optgroup label="Moldeo P1 y P2">
                                    <option>SETUP Y/O REINICIO</option>
                                    <option>MONTAR E INICIAR</option>
                                    <option>MONTAR </option>
                                    <option>E.F.M (Contaminacion)</option>
                                    <option>INITIAL BREAK</option>
                                    <option>BOND TEST</option>
                                    <option>SHORT SHOT</option>
                                    <option>FLASH</option>
                                    <option>PINCH</option>
                                    <option>BUBBLE</option>
                                    <option>MALA ALINEACION</option>
                                    <option>DAÑO DE PINS</option>
                                    <option>HUNDIMIENTO</option>
                                    <option>FALLA DE LUER</option>
                                    <option>DAÑO EN CUERPO</option>
                                    <option>VOID</option>
                                    <option>CRACK</option>
                                    <option>REVISAR MOLDE (ATORADO, NO BAJA)</option>
                                    <option>CAMBIO DE HUARACHE</option>
                                    <option>FUGAS DE AGUA</option>
                                    <option>PURGAR Y APAGAR</option>
                                    <option>PRUEBA SOFTWARE IGNORAR</option>
                                </optgroup>




                                <optgroup label="Tipping P1 y P2">  
                                    <option>SETUP Y/O REINICIO</option>
                                    <option>MONTAR E INICIAR</option>                     
                                    <option>PIEZAS ABIERTAS</option>
                                    <option>DELAMINADAS (UÑA)</option>
                                    <option>CRACK (QUEBRADURA)</option>
                                    <option>MALAS DE RADIO</option>
                                    <option>MALAS DE ID</option>
                                    <option>MALAS DE OD</option>
                                    <option>POROSAS</option>
                                    <option>DAÑO EN EL TUBO</option>
                                    <option>DAÑO EN LA PUNTA</option>
                                    <option>FLUJO</option>
                                    <option>MALA FUSION</option>
                                    <option>INCOMPLETAS</option>
                                    <option>APLASTADAS</option>
                                    <option>LIMPIEZA DE MOLDES</option>
                                    <option>PIEZAS QUEMADAS</option>
                                    <option>PIEZAS MARCADAS</option>
                                </optgroup>



                                
                                <optgroup label="Planta 3 Moldeo">
                                <option>SETUP Y/O REINICIO</option>
                                <option>MONTAR E INICIAR</option>
                                <option>MONTAR </option>
                                <option>E.F.M. (CONTAMINACION)</option>
                                <option>REVISAR MOLDE (ATORADO, NO BAJA)</option>
                                <option>SHORT SHOT</option>
                                <option>FLASH</option>
                                <option>PINCH</option>
                                <option>BUBBLE</option>
                                <option>MALA ALINEACION</option>
                                <option>DAÑO DE PINS</option>
                                <option>HUNDIMIENTO</option>
                                <option>FALLA DE LUER</option>
                                <option>DAÑO EN CUERPO</option>
                                <option>CRACK</option>
                                <option>PIEZAS DESPEGADAS</option>
                                <option>CAMBIO DE HUARACHE</option>
                                <option>FUGAS DE AGUA</option>
                                <option>PURGAR Y APAGAR</option>
                                <option>CAMBIO DE TORNILLO</option>
                                <option>LIMPIEZA DE CRYO</option>
                                <option>HACER MEZCLA Y/O MASTER BATCH</option>
                                <option>LIMPIEZA DE VASITO</option>
                                <option>HACER IRR</option>
                                <option>PIEZAS VETEADAS </option>
                                <option>CAMBIO DE FELPA PULIDORA</option>
                                <option>CAMBIO DE PANTALLA</option>
                                <option>CAMBIO DE ALCOHOL EN TANQUE</option>
                                <option>AFILADO DE BROCAS</option>
                                <option>CAMBIO DE BROCAS</option>
                                <option>CAMBIO DE NAVAJA</option>
<option>IMPRESION INCOMPLETA</option>
<option>CORTE FUERA DE ESPECIFICACION</option>
<option>ALARMADA(RESET)</option>
<option>DIMENSION FUERA DE ESPECIFICACION</option>
<option>EXTRACCION DE NAVAJAS F-16531-1</option>
<option>EXTRACCION DE NAVAJA F-16531-2</option>
                                </optgroup>

                            </select>
                            
                        </div>












                        <div class="form-group">
                            <label>Numero de orden</label><br />
                            <input type="text" class="form-control" name="orden" required>
                        </div>

                        <div class="form-group">
                            <label>Numero de parte</label><br />
                            <input type="text" class="form-control" name="parte" required>
                        </div>
                        
                        <input type="hidden" value="setup" name="tipo_de_error">
                        
                        <div class="form-group">
                            <input type="submit" name="submit_error" class="btn btn-primary btn-flat" value="Reportar Error">
                        </div>
                    


                    </div><!--disp desaprece al enviar error-->
                    
                        
                </form>
                
                
                
              
            </div><!--modal body-->
            
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>

        </div><!--modal content-->
          
    </div><!--modal-dialog end-->

</div><!-- Termina Modal -->