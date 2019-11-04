(function ($) {
    //var qty = Math.round(5.4); 
    $("#AddMatNB").on("change", function (e) {

        var data = {
            "PO": [
            ]
        };
        var data2 =
        {
            "PONO": "4100017583",
            "Customer": "Jack Wills",
            "Style": "JACKW193F-022",
            "Product": "Pant",
            "Color": {
                "Black": {
                    "10 Yrs": "297",
                    "12 Yrs": "186",
                    "14 yrs": "61",
                    "16 Yrs": "19",
                    "4 Yrs": "34",
                    "6 Yrs": "134",
                    "8 Yrs": "269"
                },
                "Charcol": {
                    "10 Yrs": "445",
                    "12 Yrs": "280",
                    "14 yrs": "92",
                    "16 Yrs": "28",
                    "4 Yrs": "51",
                    "6 Yrs": "201",
                    "8 Yrs": "403"
                },
                "Navy": {
                    "10 Yrs": "445",
                    "12 Yrs": "280",
                    "14 yrs": "92",
                    "16 Yrs": "28",
                    "4 Yrs": "51",
                    "6 Yrs": "201",
                    "8 Yrs": "403"
                }
            }
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
        let pos = data['PO'];
        pos.push(data2);
        console.log(pos);

        //console.log(pos[0].PONO);

        if (e.target.files != undefined) {
            var reader = new FileReader();
            reader.onload = function (e) {
                csvResult = e.target.result.split("\n");
                csvResult.forEach(function (element, i) {
                    if (i !== 0) {
                        var row = element.split(",");
                        var pono = row[1];

                        var colorName = row[4];
                        var sizeName = row[5];

                        for (let index in pos) {
                            const po = pos[index];
                            if (po.PONO === pono) {
                                console.log("Found: " + pono);
                                if (po.Color.hasOwnProperty(row[4])) {
                                    console.log("Color found");
                                    let size = {
                                        sizeName: Math.round(row[7])
                                    }
                                    po.Color[colorName].push(size);
                                } else {
                                    console.log("New color");
                                    let color = {
                                        colorName: {
                                            sizeName: Math.round(row[7])
                                        }
                                    }
                                    po.Color.push(color);
                                }
                            }
                            else {
                                let po =
                                {
                                    "PONO": row[1],
                                    "Customer": row[0],
                                    "Style": row[2],
                                    "Product": row[3],
                                    "Color": {
                                        colorName: {
                                            sizeName: Math.round(row[7])
                                        }
                                    }
                                };
                            }
                        }
                    }
                });
            }
            reader.readAsText(e.target.files.item(0));
        }

    });

})(jQuery);