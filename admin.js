$("#seznam-stranek").sortable({
    "update" : () => {

        //tento prikaz vytvordi pole ID z naseho seznamu sranek
        let poleId = $("#seznam-stranek").sortable("toArray");
        //console.log(poleId);

        $.ajax({
            type: "POST",
            url: "./admin.php",
            data: {
                novePoradiId: poleId //nejake pole ID 
            },
            dataType: "json",
            success: function (response) {
                
            }
        });
    }
});