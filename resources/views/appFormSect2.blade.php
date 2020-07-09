@extends('appForm')

@section('tabTitle')
2: Maklumat Peribadi Pasangan
@endsection

@section('sectBody')

<form class="form-horizontal" id="formsect2" method="post" action="{{ url('/sct2Save') }}">
    @csrf
    <input type="hidden" id="appId" name="appId" value="{{ $appForm['app_id'] ?? '' }}">
      <input type="hidden" id="act" name="act" value="{{ $loadData['act'] ?? '' }}">
    <div class="card-body">
        <div class="form-group row">
          <div class="col-sm-9">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="jikaAda" name="jikaAda" value="Jika Ada" 
              @if (isset($loadData['jikaAda'])) checked @endif>
              <label for="jikaAda" class="custom-control-label">Jika Ada</label>
            </div>
          </div>
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
              <input type="text" class="form-control" id="nama" name="nama" 
              value="{{ $appForm['nama'] ?? '' }}"></td>
          </tr>
          <tr>
            <th scope="col">No. Mykad/ KP/Tentera/Polis</th>
            <td colspan="3" scope="col">
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
              value="{{$appForm['tempat_lahir'] ?? ''}}"></td>
            <td>Tarikh Lahir</td>
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
            <td>Umur</td>
            <td>
              <span class="text-danger" for="umur" name="errumur">
                @isset($errMsg['umur'])
                {{$errMsg['umur']}}
                @endisset
              </span>
              <input type="text" class="form-control" id="umur" name="umur" value="{{$appForm['umur'] ?? ''}}" ></td>
          </tr>
          <tr>
            <th>Bangsa</th>
            <td  style="width: 25%">                
              <select class="form-control col-sm-8" name="bangsa" id="bangsa">
                <option @if ('Melayu' == ($appForm['bangsa']?? '')) selected @endif>Melayu</option>
                <option @if ('Cina' == ($appForm['bangsa']?? '')) selected @endif>Cina</option>
                <option @if ('India' == ($appForm['bangsa']?? '')) selected @endif>India</option>
                <option @if ('Lain-lain' == ($appForm['bangsa']?? '')) selected @endif>Lain-lain</option>
              </select>
          </td>
          <td colspan="2"></td>
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
              <input type="number" min=0 step=0.1 class="form-control" id="pendapatan" name="pendapatan"
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
                  <select class="form-control" name="sellesen" id="select3">
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
    </div>
    <!-- /.card-body -->
    <div class="card-footer">

      <button type="submit" id="btnsave" class="btn btn-success save-btn float-right">Simpan</button>

    </div>
    <!-- /.card-footer -->
  </form>
@endsection

@section('jsscript')
<!-- Page script -->
<script>
  $(document).ready(function(){
    if(!$('#jikaAda').prop("checked"))
    {
      $("#formsect2 :input").prop("readonly", true);
      $("#select3").prop("disabled", true);
      $("#bangsa").prop("disabled", true);
      $("#jantina").prop("disabled", true);
      $('#btnsave').prop('readonly', false);
      $('#jikaAda').prop('readonly', false);
    }
          
          
    });


  $('#jikaAda').change(function(){
      if (this.checked) {
        $("#formsect2 :input").prop("readonly", false);
        $("#select3").prop("disabled", false);
        $("#bangsa").prop("disabled", false);
        $("#jantina").prop("disabled", false);
      }else{
        
        $("#formsect2 :input").prop("readonly", true);
        $("#select3").prop("disabled", true);
        $("#bangsa").prop("disabled", true);
        $("#jantina").prop("disabled", true);
        $('#btnsave').prop('readonly', false)
        $('#jikaAda').prop('readonly', false)
      }
    });
    $(function () {
    $('#date').datetimepicker({
      format: 'DD-MM-YYYY'
    });
  })
</script>
@endsection