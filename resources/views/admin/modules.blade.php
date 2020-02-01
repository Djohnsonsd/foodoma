<form action="{{ route('admin.installModule') }}" method="POST" enctype="multipart/form-data">

	 <input type="file" class="form-control-lg form-control-uniform" name="zipfile" required>
	
	<input type="submit" value="UPLOAD">
	 @csrf
</form>