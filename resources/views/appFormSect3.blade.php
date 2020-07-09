@extends('appForm')

@section('tabTitle')
3: Maklumat Kelulusan Akademik
@endsection

@section('sectBody')
<form class="form-horizontal" method="post" action="{{ url('/sct3Save') }}">
    @csrf
    <input type="hidden" id="appId" name="appId" value="{{ $appForm['id'] ?? '' }}">
      <input type="hidden" id="act" name="act" value="{{ $loadData['act'] ?? '' }}">
    <div class="card-body">
        <label>Sila nyatakan maklumat kelulusan akademik bermula dari peringkat tertinggi diikuti dengan peringkat yang lebih rendah</label>
        <table class="table table-bordered" >
          <thead class="thead-dark">
            <tr>
              <th class="text-center align-top" rowspan="2" scope="row">No.</th>
              <th style="width: 50%" class="text-center align-top" rowspan="2">Bidang Pengajian (i.e SRP/ SPM/ Sijil/ Diploma/ Sarjana Muda)</th>
              <th class="text-center" colspan="2">Tempoh Pengajian(Tahun)</th>
            </tr>
          <tr>
            <th style="width: 20%" class="text-center">Mula</th>
            <th class="text-center">Tamat</th>
          </tr>
          </thead>
          <tbody>
            @for($i = 1; $i < 5; $i++)
            <tr>
              <th class="text-center">{{$i}}</th>
              <td class="text-center">
                <input type="text" class="form-control" id="bidang1" name="bidang[]" 
                value="{{$loadData['academic'][$i-1]['pengajian'] ?? ''}}">
              </td>
              <td>
                <div class="input-group date" id="dateMula{{$i}}" data-target-input="nearest">
                  <div class="input-group-append" data-target="#dateMula{{$i}}" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                  <input type="text" class="form-control datetimepicker-input" name="tempohMula{{$i}}"
                   data-target="#dateMula{{$i}}" data-toggle="datetimepicker"
                   value="{{isset($loadData['academic'][$i-1]['tempoh_mula']) ? date('d-m-Y',strtotime($loadData['academic'][$i-1]['tempoh_mula'])) : ''}}"/>
                </div>
              </td>
              <td>
                <div class="input-group date" id="dateTamat{{$i}}" data-target-input="nearest">
                  <div class="input-group-append" data-target="#dateTamat{{$i}}" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                  <input type="text" class="form-control datetimepicker-input" name="tempohTamat{{$i}}"
                   data-target="#dateTamat{{$i}}" data-toggle="datetimepicker"
                   value="{{isset($loadData['academic'][$i-1]['tempoh_tamat']) ? date('d-m-Y',strtotime($loadData['academic'][$i-1]['tempoh_tamat'])) : ''}}"/>
                </div>
              </td>
            </tr>
            @endfor
          </tbody>
        </table>
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
$(function () {
    $('#dateMula1').datetimepicker({
      format: 'DD-MM-YYYY'
    });
    $('#dateTamat1').datetimepicker({
      format: 'DD-MM-YYYY'
    });

    $('#dateMula2').datetimepicker({
      format: 'DD-MM-YYYY'
    });
    $('#dateTamat2').datetimepicker({
      format: 'DD-MM-YYYY'
    });

    $('#dateMula3').datetimepicker({
      format: 'DD-MM-YYYY'
    });
    $('#dateTamat3').datetimepicker({
      format: 'DD-MM-YYYY'
    });

    $('#dateMula4').datetimepicker({
      format: 'DD-MM-YYYY'
    });
    $('#dateTamat4').datetimepicker({
      format: 'DD-MM-YYYY'
    });

    
    
  });
</script>
@endsection