<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Persuratan Auto Login</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
</head>

<body>
    <main>
        <h1>AUTO LOGIN</h1>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        cekStatus();
        function cekStatus(){
            $.ajax({
                url: "{{ $url }}",
                method: "GET",
                xhrFields: {
                    withCredentials: true
                },
                success: function(respon) {
                    console.log(respon);
                    // if (respon.status) {
                    //     loginSurat(respon.data);
                    // } else {
                    //     console.log("Tidak bisa login ke Surat karena tidak login di SIMPEG.");
                    // }                
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function loginSurat(data){
            $.ajax({
                url: "{{ $url }}",
                method: "GET",
                success: function(respon) {
                    if (respon.status) {
                        loginSurat(respon.data);
                    } else {
                        console.log("Tidak bisa login ke Surat karena tidak login di SIMPEG.");
                    }                
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
</body>

</html>