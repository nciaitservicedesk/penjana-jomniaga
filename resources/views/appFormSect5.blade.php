@extends('appForm')

@section('tabTitle')
5: Perakuan Pemohon
@endsection

@section('sectBody')
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ url('/sct5Save') }}">
  @csrf
  <input type="hidden" id="appId" name="appId" value="{{ $appForm['id'] ?? '' }}">
  <div class="card-body">
      <div class="form-group row">
      </div>
      <div class="col-12">
        <span class="text-danger" style="font-weight: bold;" >
          @isset($errMsg['chkTnc'])
          {{$errMsg['chkTnc']}}<br/>
          @endisset
        </span>
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input" type="checkbox" id="chkTnc" name="chkTnc" value="checked" />
          <label for="chkTnc" class="custom-control-label">Setuju</label>
        </div>
        <ol>
           <li>Saya akui bahawa maklumat yang diberi adalah benar dan sekiranya maklumat di atas didapati palsu, Pihak Berkuasa Pelaksanaan Koridor Utara (NCIA) berhak untuk menolak permohonan saya.</li>
           <li>Sekiranya saya terpilih menyertai program ini, saya memberi kebenaran kepada NCIA untuk mendedahkan sebarang maklumat peribadi peserta kepada wakil-wakil yang dilantik oleh NCIA bagi tujuan program dan menggunakan maklumat peribadi saya di dalam semua program pembangunan modal insan di NCER.</li>
           <li>Dengan menyertai program ini, saya memberikan kebenaran dan bersetuju untuk NCIA menyiarkan, menerbitkan atau memaparkan gambar, nama dan maklumat peribadi saya di mana-mana media massa, laman sesawang atau lain-lain saluran komunikasi menurut budi bicara NCIA. Oleh yang demikian, NCIA berhak untuk menyiar atau memaparkan nama dan gambar saya bagi tujuan promosi, pengiklanan dan publisiti, tanpa sebarang bayaran atau pampasan.</li>
           <li>Saya faham bahawa permohonan saya boleh terbatal sekiranya semua dokumen sokongan yang disebutkan dalam senarai semak di bawah tidak disertakan.</li>
          </ol> 
       </div>
  </div>
  <!-- /.card-body -->
  <div class="card-footer">
    <button type="submit" class="btn btn-success save-btn float-right">Simpan dan Hantar</button>
  </div>
  <!-- /.card-footer -->
  </form>
@endsection

@section('jsscript')
<script type="text/javascript">

  $(document).ready(function () {
    bsCustomFileInput.init();
  });
</script>
@endsection