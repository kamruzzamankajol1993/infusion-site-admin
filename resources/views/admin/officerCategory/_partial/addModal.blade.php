<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Officer Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
            <form id="form" method="post" action="{{ route('officerCategory.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="addName" class="form-label">Name</label>
                            <input type="text" name="name" id="addName" class="form-control" placeholder="Name" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="addParentId" class="form-label">Parent Category</label>
                            <select name="parent_id" id="addParentId" class="form-select">
                                <option value="">-- None (Top Level) --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-sm w-md mt-2">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>