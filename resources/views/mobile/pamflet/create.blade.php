@extends('mobile.main')

@section('title','CreateData | ')

@section('breadcrumbs')
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Tugas Mingguan</h1>
                    </div>
                </div>
            </div>			
        </div>
@endsection

@section('content')
        <div class="content mt-3">
            <div class="animated fadeIn">
               <div class="card">
               	<div class="card-header">
               		<div class="pull-left">
               			<strong>Serahkan Tugas Pamflet</strong>
               		</div>
               		<div class="pull-right">
               			<a href="{{ url('pamfletk')}}" class="btn btn-success btn-sm">
               				<i class="fa fa-undo"></i> Kembali
               			</a>
               		</div>
               	</div>
               	<div class="card-body ">
               		
                  <div class="row">
                    <div class="col-md-4 offset-md-4">
                      <form action="{{url('pamfletk')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                          <table>Nama Pelapor</table>
                          <input disabled type="text" name="nama_id" class="form-control @error('nama_id') is-invalid @enderror" value="{{Auth()->user()->name}}" autofocus>
                          @error('nama_id')
                          <div class="invalid-feedback">{{$message}}</div>
                          @enderror
                        </div>
                        {{-- <div class="form-group">
                          <table>Tanggal Laporan</table>
                          <input type="date" name="tgl" class="form-control @error('tgl') is-invalid @enderror" value="{{old('tgl')}}" autofocus>
                          @error('tgl')
                          <div class="invalid-feedback">{{$message}}</div>
                          @enderror
                        </div> --}}
                        <div class="form-group">
                          <table>Tugas Gambar 1</table>
                          <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" value="{{old('gambar')}}" autofocus>
                          @error('gambar')
                          <div class="invalid-feedback">{{$message}}</div>
                          @enderror
                        </div>
                        <div class="form-group">
                          <table>Tugas Gambar 2</table>
                          <input type="file" name="gambar1" class="form-control @error('gambar1') is-invalid @enderror" value="{{old('gambar1')}}" autofocus>
                          @error('gambar1')
                          <div class="invalid-feedback">{{$message}}</div>
                          @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                      </form>
                    </div>
                  </div>
               	</div>
               </div>
            </div>
        </div>
    </div>
@endsection