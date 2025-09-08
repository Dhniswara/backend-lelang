<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midtrans Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Selesaikan Pembayaran</h2>
        <p>Klik tombol di bawah untuk melanjutkan ke pembayaran.</p>
        <button id="pay-button">Bayar Sekarang</button>
    </div>

    {{-- <script type="text/javascript">
        // Ganti dengan snap_token yang Anda dapatkan dari backend Laravel
        const snapToken = "b14dda5c-f29d-4bdf-896b-eb4b5ddcaf51";

        document.getElementById('pay-button').onclick = function(){
            // Memanggil pop-up pembayaran Midtrans dengan snap_token
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    // Callback jika pembayaran berhasil
                    alert("Pembayaran berhasil!");
                    console.log(result);
                },
                onPending: function(result){
                    // Callback jika pembayaran pending
                    alert("Menunggu pembayaran Anda.");
                    console.log(result);
                },
                onError: function(result){
                    // Callback jika terjadi error
                    alert("Pembayaran gagal!");
                    console.log(result);
                },
                onClose: function(){
                    // Callback jika pop-up ditutup oleh pengguna
                    alert('Anda menutup pop-up tanpa menyelesaikan pembayaran.');
                }
            });
        };
    </script> --}}

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key={{ env('MIDTRANS_CLIENT_KEY')}}></script>
    <script type="text/javascript">
    const snapToken = "46a04f7f-f933-4f36-bd8a-29d132e273b4";

      document.getElementById('pay-button').onclick = function(){
        snap.pay(snapToken, {
          // Optional
          onSuccess: function(result){
            /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
          },
          // Optional
          onPending: function(result){
            /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
          },
          // Optional
          onError: function(result){
            /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
          }
        });
      };
    </script>

</body>
</html>