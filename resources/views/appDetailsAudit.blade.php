@extends('adminMaster')

@section('content')
<section class="content">
  <!-- Application Status tittle--> 
<h5 class="mt-4 mb-2">Application Audit Trails</h5>
  <div class="card-body">
    <a href="{{ url()->previous() }}">
    <input type="button" id="btnback1" name="btnback1" value="&laquo;back" class="btn btn-light hBack"/>
    </a>
    <h4>{{ $refNo }}</h4>
    <br/><br/>
    <table class="table table-bordered table-sm" align="center" style="width:600px" >
      <thead>
        <tr>
          <th>
            State
          </th>
          <th>
            Approved Pax
          </th>
          <th>
            Approved Fund
          </th>
          <th>
            Responsible Officer
          </th>
          <th>
            Comment
          </th>
          <th>
            Timestamp
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($result as $row)
        <tr>
          <td>{{$row->name}}</td>
          <td>{{$row->approved_pax}}</td>
          <td>{{$row->approved_fund}}</td>
          <td>{{$row->by_name}}</td>
          <td>{{$row->comment ?? ''}}</td>
          <td>{{$row->created_at}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>



  </div>
  <!-- /.card-body -->
  <div class="card-footer">
    <a href="{{ url()->previous() }}">
      <input type="button" id="btnback2" name="btnback" value="&laquo;back" class="btn btn-light hBack"/>
    </a>
  </div>
  <!-- /.card-footer -->

</section>
@endsection
@section('jsscript2')
<script>
  
  $(document).ready(function() {

  });
</script>
@endsection














