@extends('layouts.app')

@section('title', 'Upload Data Progress')

@section('content')
<div class="container mt-4">
    <h2>Upload Data Progress Excel</h2>
    <form action="{{ route('progress.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="tahun_anggaran">Tahun Anggaran</label>
        <select name="tahun_anggaran" id="tahun_anggaran" class="form-control">
            <option value="2025">2025</option>
        </select>
    </div>

    <div class="form-group">
        <label for="file">File Excel</label>
        <input type="file" name="excel_file" id="excel_file" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Upload & Proses Data</button>
</form>

@endsection
