<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        
          <!-- Modal content-->
        <div class="modal-content">
              <div style="background-color: #337ab7; color:#ffffff;" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Reporte de <b>Armado de Molde.</b></h4>
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
                            <label>Numero de parte</label><br />
                            <input type="text" class="form-control" name="parte" list="partes" required>
                            <datalist id="partes">
                                <?php
                                $query_parts = "SELECT * FROM activeparts";
                                $result_query_parts = mysqli_query($connection, $query_parts);
                                while ($row_parts = mysqli_fetch_array($result_query_parts)):
                                ?>
                                    <option value="<?php echo $row_parts['COL 1'] ?>"><?php echo $row_parts['COL 1'] ?></option>
                                <?php endwhile; ?>
                            </datalist>
                        </div>
                        
                        <input type="hidden" value="armado" name="tipo_de_error">
                        
                        <div class="form-group">
                            <input type="submit" name="submit_error" class="btn btn-primary btn-flat" value="Pedir Molde">
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

<!--
<option>SETUP Y/O REINICIO</option>
<option>MONTAR E INICIAR</option>
-->
<script>

    document.getElementById("prioridad").style.display = 'none';

    document.getElementById("descripcion").onchange = function() {
        console.log("activado");

        var descripcion = document.getElementById("descripcion").value;
        if (descripcion === "MONTAR E INICIAR")
        {
            document.getElementById("prioridad").style.display = 'block';
            document.getElementById("prioridad-input").setAttribute("required", "");

        }
        else{
            document.getElementById("prioridad").style.display = 'none';
            document.getElementById("prioridad-input").removeAttribute("required");
        }

    }
</script>
