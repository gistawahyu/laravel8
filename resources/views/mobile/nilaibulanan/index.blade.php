@extends('mobile.main')

@section('title','Data Nilai Karyawan')

@section('breadcrumbs')
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Total Nilai Bulanan</h1>
                    </div>
                </div>
            </div>			
        </div>
@endsection

@section('content')
        <div class="content mt-3">
            <div class="animated fadeIn">

            	@if (session('status'))
    				<div class="alert alert-success">
        				{{ session('status') }}
    				</div>
			@endif

               <div class="card">
                
               	<div class="card-header">
               		<div class="pull-left">
               			<strong>Data Nilai Bulanan</strong>
               		</div>
               	</div>
               	<div class="card-body table-responsive">
                    <form role="form" action="{{ url('nilaibulanan')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-2 pr-1">
                          <div class="form-group">
                            <label>Nama Karyawan</label>
                            <input type="text" class="form-control" placeholder="Nama Karayawan" name="q" >
                          </div>
                        </div>
                        <div class="col-md-2 pr-1">
                                <div class="form-group">
                                  <label>Bulan</label>
                                  <select class="form-control" name="bulan1"   style="height:35px;">
                                  <option value="">-- Bulan --</option>
                                  <option value="1">Januari</option>
                                  <option value="2">Fabruari</option>
                                  <option value="3">Maret</option>
                                  <option value="4">April</option>
                                  <option value="5">Mei</option>
                                  <option value="6">Juni</option>
                                  <option value="7">Juli</option>
                                  <option value="8">Agustus</option>
                                  <option value="9">September</option>
                                  <option value="10">Oktober</option>
                                  <option value="11">November</option>
                                  <option value="12">Desember</option>
                                  </select>
                                  </div>
                        </div><div class="col-md-2 pr-1">
                                <div class="form-group">
                                  @php
                                  $tahun = DB::select("select DISTINCT YEAR(tgl) as tahun from rekap");
                                  @endphp
                                  <label>Tahun</label>
                                  <select class="form-control" name="tahun1"   style="height:35px;">
                                  <option value="">-- Tahun --</option>
                                  @foreach ($tahun as $t)
                                  <option value="{{$t->tahun}}">{{$t->tahun}}</option>
                                  @endforeach
                                  </select>
                                </div>
                        </div>
                        <div class="col-md-2 pr-1">
                                <div class="form-group">
                                  <label>Sorting Nilai Berdasarkan</label>
                                  <select class="form-control" name="orderBy"   style="height:35px;">
                                  <option value="">Semua</option>
                                  <option value="0">Nilai Terendah</option>
                                  <option value="1">Nilai Tertinggi</option>
                                  </select>
                                </div>
                        </div>
                        <div class="col-md-1 pr-1">
                                <div class="form-group">
                                  <label style="color:white;">,l</label>
                                 <br><button class="btn btn-primary" type="submit"><i class="fa fa-search"> Search </i></button>
                                </div>
                        </div>       
                        <div class="col-md-1 pr-1">
                                <div class="form-group">
                                  <label style="color:white;">,l</label>
                                 <br><a class="btn btn-danger" href="{{ url('nilaibulanan')}}"><i class="fa fa-refresh"> Refresh </i></a>
                                </div>
                        </div>
                        </form>
                    <table class="table table-bordered">
                    <thead>
                      <tr class="text-center">
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Bulan</th>
                        <th>Total Nilai Harian</th>
                        <th>Total Nilai Mingguan</th>
                        <th>Total Nilai Bulanan</th>
                        <th>Jml Tugas</th>
                        <th>Rata - rata</th>
                        <th>Predikat</th>
                      
                      </tr>
                    </thead>
                    @php
                    $i = 1;
                    @endphp
                    <tbody>
                    @foreach ($best as $b)
                    <tr>
                    <td>{{$i++}}</td>
                    <td>{{$b->name}}</td>
                    <td>{{$b->bulan}} - {{$b->tahun}}</td>
                    <td>{{$b->harian}}</td>
                    <td>{{$b->mingguan}}</td>
                    <td>{{$b->bulanan}}</td>
                    <td>{{$b->jmltugas}}</td>
                    <td>{{$b->hasil}}</td>
                    @php
                    $p = "E";
                    if($b->hasil <= 10 && $b->hasil > 9){
                      $p = "A";
                    }else if ($b->hasil <= 9 && $b->hasil > 8){
                      $p = "B+";
                    }else if ($b->hasil <= 8 && $b->hasil > 7){
                      $p = "B";
                    }else if ($b->hasil <= 7 && $b->hasil > 6){
                      $p = "B-";
                    }else if ($b->hasil <= 6 && $b->hasil > 5){
                      $p = "C+";
                    }else if ($b->hasil <= 5 && $b->hasil > 4){
                      $p = "C";
                    }else if ($b->hasil <= 4 && $b->hasil > 3){
                      $p = "C-";
                    }else if ($b->hasil <= 3 && $b->hasil > 2){
                      $p = "D+";
                    }else if ($b->hasil <= 2 && $b->hasil > 1){
                      $p = "D";
                    }else if ($b->hasil <= 1 && $b->hasil > 0){
                      $p = "D-";
                    }
                    @endphp
                    <td>{{$p}}</td>
                    
                    <tr>
                    @endforeach
                    </tbody>
                 </table>
                 </div>
            </div>
        </div>
    </div>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    var year = [<?php foreach ($best as $key) { ?>
            '<?php echo $key->name ?>',
        <?php }?>];
    var nilai = [<?php foreach ($best as $key) { ?>
            '<?php echo $key->bulanan ?>',
        <?php }?>];
    var barChartData = {
        labels: year,
        datasets: [{
            label: 'Jumlah Nilai',
            backgroundColor: "pink",
            data: nilai
        }]
    };

    window.onload = function() {
        var ctx = document.getElementById("myChart").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                },scales: {
                    yAxes: [{
                        ticks: {
                        beginAtZero:true
                        }
                    }]
                }
            }
        });
    };
</script>
@endsection