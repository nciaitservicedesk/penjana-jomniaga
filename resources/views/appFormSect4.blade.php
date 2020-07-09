@extends('appForm')

@section('tabTitle')
4: Kursus Yang Dipilih
@endsection

@section('sectBody')
<form class="form-horizontal" method="post" action="{{ url('/sct4Save') }}">
    @csrf
    <input type="hidden" id="appId" name="appId" value="{{ $appForm['id'] ?? '' }}">
      <input type="hidden" id="act" name="act" value="{{ $loadData['act'] ?? '' }}">
    <div class="card-body">
      <span class="text-danger" for="select1" name="errnama">
        @isset($errMsg['err'])
        {{$errMsg['err']}}
        @endisset
      </span>
        <div class="form-group row">
          <label  class="col-sm-10 col-form-label">Sila pilih kursus yang disediakan untuk program Penjana Jom Niaga</label>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label">Pilihan Pertama</label>
          <div class="col-sm-6">
            <select class="form-control" name="sel1" id="select1">
              @foreach ($loadData['kursus'] as $kursus)
            <option value="{{$kursus['id']}}" @if ($kursus['id'] == ($appForm['kursus_pertama_id']?? '')) selected @endif>{{$kursus['nama']}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-4 col-form-label">Jika Pilih Lain-lain, Sila Nyatakan:</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="lain1" name="lain1" value="{{ $appForm['kursus_pertama_lain'] ?? '' }}">
          </div>
        </div>

        <div class="form-group row">
          <label  class="col-sm-10 col-form-label"></label>
        </div>

        <div class="form-group row">
          <label class="col-sm-4 col-form-label">Pilihan Kedua</label>
          <div class="col-sm-6">
            <select class="form-control" name="sel2" id="select2">
              @foreach ($loadData['kursus'] as $kursus)
              <option value="{{$kursus['id']}}" @if ($kursus['id'] == ($appForm['kursus_kedua_id']?? '')) selected @endif>{{$kursus['nama']}}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label">Jika Pilih Lain-lain, Sila Nyatakan:</label>
          <div class="col-sm-6">
          <input type="text" class="form-control" id="lain2" name="lain2" value="{{ $appForm['kursus_kedua_lain'] ?? '' }}">
          </div>
        </div>
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
