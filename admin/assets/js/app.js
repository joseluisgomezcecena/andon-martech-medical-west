document.addEventListener("DOMContentLoaded", () => {
    // const planta_select = document.getElementById("planta");
    const search_btn = document.getElementById("buscar_btn");
    const spinner = document.getElementById("spinner");
    
    // planta_select.addEventListener("change", get_machines);
    search_btn.addEventListener("click", get_data);
});

function get_machines(e) {
    const machines_datalist = document.getElementById("machines_list");
    const output_div = document.getElementById("output");
    let data_post = new FormData(),
        xmlhttp   = new XMLHttpRequest();

    data_post.append("planta", e.target.value);

    xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4 & this.status == 200) {
            let data = JSON.parse(this.responseText);
            document.getElementById("maquina").value = ""

            if(data.ok) {
                machines_datalist.innerHTML = ''
                let { items } = data;
                items.forEach(item => {
                    machines_datalist.innerHTML += `<option value="${item.id} ${item.machine}" />`;
                });
            } else {
                swal("Error", data.error, "error");
            }
        }
    }

    xmlhttp.open("POST", "config/API.php?fn=get_machines", true);
    xmlhttp.send(data_post);
}

// function get_data() {
//     let maquina   = document.getElementById("maquina").value,
//         start     = document.getElementById("start").value,
//         end       = document.getElementById("end").value,
//         data_post = new FormData(),
//         xmlhttp   = new XMLHttpRequest();

//     if(!maquina) {
//         swal("Maquina no seleccionada", "No se ha seleccionado una maquina", "warning");
//         return false;
//     } else if(!start) {
//         swal("La fecha de inicio no ha sido seleccionada", "No se ha seleccionado una fecha de inicio", "warning");
//         return false; 
//     } else if(!end) {
//         swal("La fecha de fin no ha sido seleccionada", "No se ha seleccionado una fecha de fin", "warning");
//         return false;
//     } 

//     data_post.append("id", maquina.split(" ")[0]);
//     data_post.append("start", start);
//     data_post.append("end", end);

//     xmlhttp.onreadystatechange = function() {
//         if(this.readyState == 4 && this.status == 200) {
//             const data_table = document.querySelector("#data_table tbody");
//             const output_div = document.getElementById("output");
//             let data = JSON.parse(this.responseText);

//             if(data.ok) {

//                 let error = document.getElementById("error");
//                 if(error) {
//                     error.remove();
//                 }

//                 let { items, results } = data;
//                 data_table.innerHTML = '';
//                 items.forEach((item, i) => {
//                     let row = `<tr>
//                                     <td>${i+1}</td>
//                                     <td>${item.inicio}</td>
//                                     <td>${item.atendido}</td>
//                                     <td>${item.fin}</td>
//                                     <td>${item.tbf}</td>
//                                     <td>${item.tr}</td>
//                                </tr>`
//                     data_table.innerHTML += row;
//                 });

//                 results = results[0];
//                 let mtbf = results.tbf_total / results.count,
//                     mttr = results.tr_total / results.count;


//                 maquinaText.innerHTML = `<h4>${maquina.slice(maquina.indexOf(" "), maquina.lastIndexOf(" "))}</h4>`;
//                 mttfText.innerHTML = `<h4>MTTF: ${mtbf.toFixed(2)} min</h4>`;
//                 mttrText.innerHTML = `<h4>MTTR: ${mttr.toFixed(2)} min</h4>`;
                
//                 let fechaInStr = `${start.split("-")[1]}/${start.split("-")[2]}/${start.split("-")[0]}`;
//                 let fechaFiStr = `${end.split("-")[1]}/${end.split("-")[2]}/${end.split("-")[0]}`;

//                 $('#box-title-report').text(`Reporte MTTF & MTTR por maquina en las fechas del ${fechaInStr} al ${fechaFiStr}`);
//                 createHistogram(data_post)
//             } else {
//                 swal("Error", data.error, "error");
//                 output_div.innerHTML += `
//                     <div class="alert alert-danger col-sm-12" id="error">
//                         <strong>Error:</strong> ${data.error}
//                     </div>
//                 `;
//                 data_table.innerHTML = '';
//                 document.getElementById('histogram').innerHTML = ''
//             }
//         }
//     }
//     xmlhttp.open("POST", "config/API.php?fn=get_data", true);
//     xmlhttp.send(data_post);

// }

function get_data() {

    spinner.style.display = "block";


    let full      = document.getElementById("full").checked,
        start     = document.getElementById("start").value,
        end       = document.getElementById("end").value,
        top       = document.getElementById("top").value,
        data_post = new FormData(),
        xmlhttp   = new XMLHttpRequest();

    if(!full) {
        if(!start) {
            swal("La fecha de inicio no ha sido seleccionada", "No se ha seleccionado una fecha de inicio", "warning");
            return false; 
        } else if(!end) {
            swal("La fecha de fin no ha sido seleccionada", "No se ha seleccionado una fecha de fin", "warning");
            return false;
        } 
    }

    if(!top) {
        swal("El número de top no es valido", "El número de top no es valido", "warning");
        return false;
    }

    data_post.append("full", full);
    data_post.append("start", start);
    data_post.append("end", end);
    // Funcion para mostrar spinner

    xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            const data_table = document.querySelector("#data_table tbody");
            const output_div = document.getElementById("output");
            let data = JSON.parse(this.responseText);

            if(data.ok) {
                let error = document.getElementById("error");
                if(error) {
                    error.remove();
                }

                let top_three_fails   = [];
                let top_three_repairs = [];

                let { results } = data;
                data_table.innerHTML = '';
                results.forEach((item, i) => {
                    let row = `<tr>
                                    <td>${i+1}</td>
                                    <td>${item.maquina}</td>
                                    <td>MMW${item.planta}</td>
                                    <td>${item.tbf_total.toFixed(2)}</td>
                                    <td>${item.tr_total.toFixed(2)}</td>
                                    <td>${item.count}</td>
                               </tr>`
                    data_table.innerHTML += row;
                });

                top_three_fails   = [...results.sort(function(a, b){return b.tbf_total - a.tbf_total})];
                top_three_repairs = [...results.sort(function(a, b){return b.tr_total - a.tr_total})];

                // top_three_repairs = top_three_repairs.filter(item => item.tr_total !== 0);
                // console.log(top_three_repairs);

                let top_ten_fails_mach     = [];
                let top_ten_repairs_mach   = [];
                let top_ten_fails_time     = [];
                let top_ten_repairs_time   = [];

                mttfText.innerHTML = `<h4>MTTF Top ${top}:</h4>`;
                mttrText.innerHTML = `<h4>MTTR Top ${top}:</h4>`;
                
                for(let i = 0; i < top; i++) {
                    let maquina = top_three_fails[i].maquina;
                    let tbf_total = top_three_fails[i].tbf_total.toFixed(2);

                    mttfText.innerHTML += `<p style="margin-left: 20px">${maquina}: <strong>${tbf_total} min</strong></p>`;

                    let tr_total = top_three_repairs[i].tr_total.toFixed(2);
                    maquina = top_three_repairs[i].maquina;
                    mttrText.innerHTML += `<p style="margin-left: 20px">${maquina}: <strong>${tr_total} min</strong></p>`;

                    top_ten_fails_mach.push(top_three_fails[i].maquina);
                    top_ten_fails_time.push(top_three_fails[i].tbf_total.toFixed(2));

                    top_ten_repairs_mach.push(top_three_repairs[i].maquina);
                    top_ten_repairs_time.push(top_three_repairs[i].tr_total.toFixed(2));
                }

                if(full)
                    $('#box-title-report').text(`Reporte MTTF & MTTR general`);
                else {
                    let fechaInStr = `${start.split("-")[1]}/${start.split("-")[2]}/${start.split("-")[0]}`;
                    let fechaFiStr = `${end.split("-")[1]}/${end.split("-")[2]}/${end.split("-")[0]}`;
                    $('#box-title-report').text(`Reporte MTTF & MTTR en las fechas del ${fechaInStr} al ${fechaFiStr}`);
                }
            
                
                createHistogram({"maquinas": top_ten_fails_mach, "tiempos": top_ten_fails_time}, {"maquinas": top_ten_repairs_mach, "tiempos": top_ten_repairs_time})
                spinner.style.display = "none";
            } else {
                swal("Error", data.error, "error");
                output_div.innerHTML += `
                    <div class="alert alert-danger col-sm-12" id="error">
                        <strong>Error:</strong> ${data.error}
                    </div>
                `;
                data_table.innerHTML = '';
                document.getElementById('histogram').innerHTML = ''
                spinner.style.display = "none";
            }
        }
    }
    xmlhttp.open("POST", "config/API.php?fn=get_full_data", true);
    xmlhttp.send(data_post);

}

function createHistogram(top_ten_fails, top_ten_repairs) {
    document.getElementById('histogram-mttf').innerHTML = '<canvas id="histogramMttf"></canvas>'
    document.getElementById('histogram-mttr').innerHTML = '<canvas id="histogramMttr"></canvas>'


    const ctx = document.getElementById('histogramMttf').getContext('2d');
    const cty = document.getElementById('histogramMttr').getContext('2d');
    document.getElementById('histogram-mttf').style.display = "block";
    document.getElementById('histogram-mttr').style.display = "block";
    

    var histogramChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: top_ten_fails.maquinas,
            datasets: [{
                label: 'Mean Time To Failure (Min)',
                data: top_ten_fails.tiempos,
                backgroundColor: 'rgba(204, 0, 0, 1)', // Red
                borderColor: 'rgba(0, 0, 0, 1)',
                barPercentage: 1,
                categoryPercentage: 1
            }]
        }
    });

    var histogramChart2 = new Chart(cty, {
        type: 'bar',
        data: {
            labels: top_ten_repairs.maquinas,
            datasets: [{
                label: 'Mean Time To Repair (Min)',
                data: top_ten_repairs.tiempos,
                backgroundColor: 'rgba(0, 204, 0, 1)', // Green
                borderColor: 'rgba(0, 0, 0, 1)',
                barPercentage: 1,
                categoryPercentage: 1
            }]
        }
    });
            

}