$(document).ready(function () {
    function hasMatch(JSON, key, value) {
        var hasMatch = false;
        for (var index = 0; index < JSON.length; ++index) {

            var line = JSON[index];

            if (line[key] == value) {
                hasMatch = true;
                break;
            }
        }
        return hasMatch;
    }
    //var qty = Math.round(5.4); 
    $("#AddMatNB").on("change", function (e) {

        var data = {
            "PO": [
            ]
        };

        var ext = $("#AddMatNB").val().split(".").pop().toLowerCase();

        if ($.inArray(ext, ["csv"]) == -1) {
            Swal.fire(
                'Import Process Canceled',
                'Proccess canceled due to a detected file error. You may try again!',
                'info'
            )
            return false;
        }

        if (e.target.files != undefined) {
            var reader = new FileReader();
            reader.onload = function (e) {
                csvResult = $.csv.toObjects(e.target.result);
                csvResult.forEach(function (row, i) {

                    var ponoCSV = row.CustomerPONumber;

                    var colorNameCSV = row.color;
                    var sizeNameCSV = row.size;
                    var CutQtyCSV = Math.round(row.CutQty).toString();

                    if (data.PO.length != 0) {
                        for (let index in data.PO) {
                            //console.log(data.PO[index]);
                            if (data.PO[index].PONO === ponoCSV) {

                                //console.log("Found: " + ponoCSV);
                                if (data.PO[index].Color.hasOwnProperty(row.color)) {

                                    //console.log("Color found " + row.color);

                                    data.PO[index].Color[colorNameCSV][sizeNameCSV] = CutQtyCSV;

                                } else {

                                    //console.log("New color " + row.color);

                                    let color = JSON.parse('{"' + colorNameCSV + '": {"' + sizeNameCSV + '":"' + CutQtyCSV + '"}}');
                                    data.PO[index].Color = color;

                                }
                            }
                            else {
                                if (!hasMatch(data.PO, "PONO", ponoCSV)) {
                                    //console.log("New PO: " + row.CustomerPONumber);
                                    let ponew2 =
                                    {
                                        "PONO": row.CustomerPONumber,
                                        "Customer": row.customer,
                                        "Style": row.StyleNumber,
                                        "Product": row.product,
                                        "Color": {}
                                    };
                                    let color = JSON.parse('{"' + colorNameCSV + '": {"' + sizeNameCSV + '":"' + CutQtyCSV + '"}}');
                                    ponew2.Color = color;
                                    data['PO'].push(ponew2);
                                }

                            }
                        }
                    } else {
                        //console.log("New PO: " + row.CustomerPONumber);
                        let ponew1 =
                        {
                            "PONO": row.CustomerPONumber,
                            "Customer": row.customer,
                            "Style": row.StyleNumber,
                            "Product": row.product,
                            "Color": {}
                        };
                        let color = JSON.parse('{"' + colorNameCSV + '": {"' + sizeNameCSV + '":"' + CutQtyCSV + '"}}');
                        ponew1.Color = color;
                        data['PO'].push(ponew1);
                    }
                });
                console.log(data.PO);
            }
            reader.readAsText(e.target.files.item(0));
        }
    });
});