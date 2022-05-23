<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div style="background-color: #337ab7; color:#ffffff;" class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Reporte de <b>Falta de Energia</b></h4>
            </div>
            
            <div class="modal-body">
              
              
               
                
                <form method="post" onsubmit="myFunction1()">
                    
                    <div id="demo"></div>       
                    

                    <div id="disp">
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


                        <div class="form-group">
                            
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
                                    <option><?php echo $row_sql['user_nombre']." ".$row_sql['user_apellido']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        
                        </div>

                    
                        <div class="form-group">
                            <label>Describe el problema si lo consideras necesario</label>
                            <textarea class="form-control" name="descripcion" rows="7" ></textarea>
                        </div>

                        <div  class="form-group">
                            <label>Folio de orden de mantenimiento</label><br />
                            <input class="form-control" name="folio_mantenimiento" required>
                        </div>
                    
                        <input type="hidden" value="energia" name="tipo_de_error">
                        
                        <div class="form-group">
                            <input type="submit" name="submit_error" class="btn btn-primary btn-flat" value="Reportar Error">
                        </div>
                    
                    </div>

                    
                </form>
                
                
                
              
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
          
        </div>
      </div>
      <!-- Termina Modal -->