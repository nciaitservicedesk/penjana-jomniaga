@extends('appForm')

@section('tabTitle')
1: Maklumat Peribadi Pemohon
@endsection

@section('sectBody')
<meta name="csrf-token" content="{{ csrf_token() }}">
<form class="form-horizontal" method="post" action="{{ url('/sct1Save') }}">
    @csrf
    <div class="card-body">
      <input type="hidden" id="appId" name="appId" value="{{ $appForm['id'] ?? '' }}">
      <input type="hidden" id="act" name="act" value="{{ $loadData['act'] ?? '' }}">
        <div class="form-group row">
          <label  class="col-sm-6 col-form-label"></label>
        </div>

        <!-- Table 1 --> 
      <table class="table table-bordered" >
        <thead class="thead-dark">
        </thead>
        <tbody>
          <tr>
            <th scope="col">Nama Penuh (seperti dalam Mykad)</td>
              
            <td colspan="3" scope="col">
              <span class="text-danger" for="nama" name="errnama">
                @isset($errMsg['nama'])
                {{$errMsg['nama']}}
                @endisset
              </span>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $appForm['nama'] ?? '' }}"></td>
          </tr>
          <tr>
            <th scope="row">No. Mykad/KP/Tentera/Polis</th>
            <td colspan="3">
              <span class="text-danger" for="mykad" name="errmykad">
                @isset($errMsg['mykad'])
                {{$errMsg['mykad']}}
                @endisset
              </span>
              <input type="number" min=0 oninput="validity.valid||(value='');" class="form-control" 
              id="mykad" name="mykad" value="{{$appForm['mycard'] ?? ''}}"></td>
          </tr>
          <tr>
            <th scope="row">Tempat Lahir</th>
            <td>
              <span class="text-danger" for="tempat" name="errtempat">
                @isset($errMsg['tempat'])
                {{$errMsg['tempat']}}
                @endisset
              </span>
              <input type="text" class="form-control" id="tempat" name="tempat" 
              value="{{$appForm['tempat_lahir'] ?? ''}}">
            </td>
            <th>Tarikh Lahir</th>
            <td>
              <span class="text-danger" for="tarikhlahir" name="errtarikhlahir">
                @isset($errMsg['tarikhlahir'])
                {{$errMsg['tarikhlahir']}}
                @endisset
              </span>
              <div class="input-group date" id="date" data-target-input="nearest">
              <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
              
              <input type="text" class="form-control datetimepicker-input" 
              id="tarikhlahir" name="tarikhlahir" 
              value="{{isset($appForm['tarikh_lahir']) ? date('d-m-Y',strtotime($appForm['tarikh_lahir'])) : ''}}"
               data-target="#date" data-toggle="datetimepicker"/>
              
          </div></td>
          </tr>
          <tr>
            <th scope="row">Jantina</th>
            <td>              
              <select class="form-control col-sm-8" name="jantina" id="jantina">
                <option @if ('Lelaki' == ($appForm['jantina']?? '')) selected @endif>Lelaki</option>
                <option @if ('Perempuan' == ($appForm['jantina']?? '')) selected @endif>Perempuan</option>
              </select>
          </td>
            <th>Umur</th>
            <td>
              <span class="text-danger" for="umur" name="errumur">
                @isset($errMsg['umur'])
                {{$errMsg['umur']}}
                @endisset
              </span>
              <input type="number" class="form-control" id="umur" name="umur" value="{{$appForm['umur'] ?? ''}}"></td>
          </tr>
          <tr>
            <th scope="row">Status Perkahwinan</th>
            <td style="width: 25%">
                <select class="form-control col-sm-8" name="statuskahwin" id="status">
                  <option @if ('Bujang' == ($appForm['status_kahwin']?? '')) selected @endif>Bujang</option>
                  <option @if ('Berkahwin' == ($appForm['status_kahwin']?? '')) selected @endif>Berkahwin</option>
                  <option @if ('Janda' == ($appForm['status_kahwin']?? '')) selected @endif>Janda</option>
                  <option @if ('Duda' == ($appForm['status_kahwin']?? '')) selected @endif>Duda</option>
                </select>
            </td>
            <th>Bangsa</th>
            <td style="width: 25%">                
              <select class="form-control col-sm-8" name="bangsa" id="bangsa">
                <option @if ('Melayu' == ($appForm['bangsa']?? '')) selected @endif>Melayu</option>
                <option @if ('Cina' == ($appForm['bangsa']?? '')) selected @endif>Cina</option>
                <option @if ('India' == ($appForm['bangsa']?? '')) selected @endif>India</option>
                <option @if ('Lain-lain' == ($appForm['bangsa']?? '')) selected @endif>Lain-lain</option>
              </select>
          </td>
          </tr>
          <tr>
            <th scope="row">No. Telefon Bimbit</th>
            <td colspan="3">
              <span class="text-danger" for="telefon" name="errtelefon">
                @isset($errMsg['telefon'])
                {{$errMsg['telefon']}}
                @endisset
              </span>
              <input type="number" min=0 oninput="validity.valid||(value='');" class="form-control" 
              id="telefon" name="telefon" value="{{$appForm['no_fon'] ?? ''}}"></td>
          </tr>
          <tr>
            <th scope="row">Alamat</th>
            <td style="width: 75%" colspan="3">
              <span class="text-danger" for="alamat" name="errnalamat">
                @isset($errMsg['alamat'])
                {{$errMsg['alamat']}}
                @endisset
              </span>
              <input type="text" class="form-control" id="alamat" name="alamat" value="{{$appForm['alamat'] ?? ''}}">
              <label></label>


              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Negeri</label>
                <div class="col-sm-3">
                  <select class="form-control" name="selNegeri" id="selNegeri">
                    @foreach ($loadData['negeri'] as $negeri)
                  <option value="{{$negeri['id']}}" @if ($negeri['id'] == ($appForm['negeri_id'] ?? '')) selected @endif>
                    {{$negeri['nama']}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-1">
                </div>
                <label class="col-sm-2 col-form-label">District</label>
                <div class="col-sm-4">
                  <select class="form-control" name="selDist" id="selDist">
                    @foreach ($loadData['district'] as $district)
                  <option value="{{$district['id']}}" @if ($district['id'] == ($appForm['district_id'] ?? '')) selected @endif>
                    {{$district['nama']}}</option>
                    @endforeach
                </select>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th scope="row">FB/ Instagram ID</th>
            <td colspan="3">
              <input type="text" class="form-control" id="fbinsta" name="fbinsta" 
              value="{{$appForm['fb'] ?? ''}}" placeholder="" ></td>
          </tr>
          <tr>
            <th scope="row">Pendapatan Bulanan</th>
            <td colspan="3">
              <span class="text-danger" for="pendapatan" name="errpendapatan">
                @isset($errMsg['pendapatan'])
                {{$errMsg['pendapatan']}}
                @endisset
              </span>
              <input type="text" type="number" min=0 step=0.1 class="form-control" id="pendapatan" name="pendapatan"
              value="{{$appForm['pendapatan'] ?? ''}}"></td>
          </tr>
          <tr>
            <th scope="row">Nama Majikan/ Perniagaan</th>
            <td colspan="3">
              <input type="text" class="form-control" id="namamajikan" name="namamajikan" 
              value="{{$appForm['nama_majikan'] ?? ''}}" placeholder="" ></td>
          </tr>
          <tr>
            <th scope="row">Alamat Majikan/ Perniagaan</th>
            <td colspan="3">
              <input type="text" class="form-control" id="alamatmajikan" name="alamatmajikan" 
              value="{{$appForm['alamat_majikan'] ?? ''}}" placeholder="" ></td>
          </tr>
          <tr>
            <th scope="row">Lesen Memandu</th>
            <td colspan="3">
              <div class="form-group row">
                <div class="col-sm-4">
                  <select class="form-control" name="sellesen" id="select1">
                    <option @if ('B2' == ($appForm['lesen_memandu']?? '')) selected @endif>B2</option>
                    <option @if ('D' == ($appForm['lesen_memandu']?? '')) selected @endif>D</option>
                    <option @if ('Lain-lain' == ($appForm['lesen_memandu']?? '')) selected @endif>Lain-lain</option>
                    <option @if ('Tiada' == ($appForm['lesen_memandu']?? '')) selected @endif>Tiada</option>
                  </select>
                </div>
              </td>
          </tr>
        </tbody>
      </table>

      <div class="form-group row">
        <label  class="col-sm-10 col-form-label"></label>
      </div>

       <table class="table table-bordered" name="tbl1">
        <thead class="thead-dark">
          <tr>
            <th class="text-center" colspan="5" scope="col">Maklumat Tanggungan (Ahli Keluarga)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">No.</th>
            <th>Nama</th>
            <th>Hubungan</th>
            <th>Umur</th>
            <th style="width: 30%">Bekerja/Tidak Bekerja</th>
          </tr>
          @for($i = 1; $i < 7; $i++)
          <tr>
            <th scope="row">{{$i}}</th>
            <td>
              <span class="text-danger" for="tbl1" name="errnama">
                @isset($errMsg['keluarga'][$i-1]['nama'])
                {{$errMsg['keluarga'][$i-1]['nama']}}
                @endisset
              </span>
              <input type="text" class="form-control" id="namaKeluarga" name="namaKeluarga[]" 
              value="{{$loadData['keluarga'][$i-1]['nama'] ?? ''}}"></td>
            <td>
              <span class="text-danger" for="tbl1" name="errhubungan">
                @isset($errMsg['keluarga'][$i-1]['hubungan'])
                {{$errMsg['keluarga'][$i-1]['hubungan']}}
                @endisset
              </span>
              <input type="text" class="form-control" id="hubungan" name="hubungan[]" 
              value="{{$loadData['keluarga'][$i-1]['hubungan'] ?? ''}}"></td>
            <td>
              <span class="text-danger" for="tbl1" name="errumur2">
                @isset($errMsg['keluarga'][$i-1]['umur'])
                {{$errMsg['keluarga'][$i-1]['umur']}}
                @endisset
              </span>
              <input type="text" class="form-control" id="umurKerluarga" name="umurKerluarga[]" 
              value="{{$loadData['keluarga'][$i-1]['umur'] ?? ''}}"></td>
            <td>
              <span class="text-danger" for="tbl1" name="errAdaKerja">
                @isset($errMsg['keluarga'][$i-1]['ada_kerja'])
                {{$errMsg['keluarga'][$i-1]['ada_kerja']}}
                @endisset
              </span><br/>
              <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="rdoKerja{{$i}}" id="inlineRadioRow{{$i}}AY" value="1"
              @if ('1' == ($loadData['keluarga'][$i-1]['ada_kerja']?? '')) checked @endif>
              <label class="form-check-label" for="inlineRadioRow{{$i}}AY">Bekerja</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="rdoKerja{{$i}}" id="inlineRadioRow{{$i}}AN" value="0"
                @if ('0' == ($loadData['keluarga'][$i-1]['ada_kerja']?? '')) checked @endif>
                <label class="form-check-label" for="inlineRadioRow{{$i}}AN">Tidak Bekerja</label>
              </div>
            </td>
          </tr>
          @endfor
        </tbody>
      </table>
      <div class="form-group row">
        <label  class="col-sm-10 col-form-label"></label>
      </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <button type="submit" class="btn btn-success save-btn float-right">Simpan</button>
    </div>
    <!-- /.card-footer -->



  </form>
@endsection
@section('jsscript')
<!-- Page script -->
<script>
  /*
var $select1 = $( '#selNegeri' ),
		$select2 = $( '#select2' ),
    $options = $select2.find( 'option' );
    
$select1.on( 'change', function() {
	$select2.html( $options.filter( '[value="' + this.value + '"]' ) );
} ).trigger( 'change' );
*/
$(document).ready(function(){

$("#selNegeri").change(function(){
    var negeriId = $(this).val();

    $.ajax({
        url: "{{ url('/getDistrictList') }}"+"/"+negeriId,
        type: 'post',
        data: {negeriId:negeriId},
        dataType: 'json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(response){

            var len = response.length;
            $("#selDist").empty();
            for( var i = 0; i<len; i++){
                var id = response[i]['id'];
                var name = response[i]['nama'];
                
                $("#selDist").append("<option value='"+id+"'>"+name+"</option>");

            }
        }
    });
});

});

$(function () {
    $('#date').datetimepicker({
      format: 'DD-MM-YYYY'
    });

  });
</script>
@endsection
