@extends('layout')

@section('content')

<div class="card">
    <h2>Corrigir Prova</h2>

    <input type="file" id="inputImagem">

    <br><br>

    <img id="preview" style="max-width:100%;">

    <br>

    <button onclick="enviar()">Corrigir</button>
</div>

<script>
let cropper;

document.getElementById('inputImagem').addEventListener('change', function(e){
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = function(event){
        const img = document.getElementById('preview');
        img.src = event.target.result;

        if(cropper) cropper.destroy();

        cropper = new Cropper(img, {
            viewMode: 1
        });
    }

    reader.readAsDataURL(file);
});

function enviar(){
    const canvas = cropper.getCroppedCanvas();

    canvas.toBlob(function(blob){

        let formData = new FormData();
        formData.append('imagem', blob);

        fetch("{{ route('provas.corrigir', 1) }}", {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            alert("Corrigido!");
        });
    });
}
</script>

@endsection