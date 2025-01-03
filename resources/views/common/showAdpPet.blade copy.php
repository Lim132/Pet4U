@extends('layouts.app')

@section('content')

    <div class="container-fluid">
      <div class="row" style="margin-top: 10px;">
        <div class="col-md-2">
          <div class="list-group">
            <a href="shopNow.html" class="list-group-item list-group-item-action active">Category</a>
            <a href="drinks.html" class="list-group-item list-group-item-action" style="text-decoration: none;">Cat</a>
            <a href="snacks.html" class="list-group-item list-group-item-action" style="text-decoration: none;">Dog</a>
            <a href="chocolate.html" class="list-group-item list-group-item-action" style="text-decoration: none;">Other</a>
          </div>
          <br>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-8">
          <div class="card border-0">
            <h5 class="title1 card-title">Pets for Adoption</h5>
            <div class="row">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="pet-title">Meow Meow</h5>
                    <img src="image/cat/Cat1_1.jpg" class="pet-img card-img-bottom" alt="">
                    <div class="card-heading"></div>
                    <button class="btn btn-danger btn-xs">See the Details</button>
                  </div>
                </div><br>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="pet-title">Susu</h5>
                    <img src="image/cat/Cat2_1.jpg" class="pet-img card-img-bottom" alt="">
                    <div class="card-heading"></div>
                    <button class="btn btn-danger btn-xs">See the Details</button>
                  </div>
                </div><br>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="pet-title">La La</h5>
                    <img src="image/dog/Dog3.jpg" class="pet-img card-img-bottom" alt="">
                    <div class="card-heading"></div>
                    <button class="btn btn-danger btn-xs">See the Detail</button>
                  </div>
                </div><br>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="pet-title">Kecil</h5>
                    <img src="image/cat/Cat3_1.jpg" class="pet-img card-img-bottom" alt="">
                    <div class="card-heading"></div>
                    <button class="btn btn-danger btn-xs">See the Detail</button>
                  </div>
                </div><br>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="pet-title">Ah Q</h5>
                    <img src="image/dog/Dog1-1.jpg" class="pet-img card-img-bottom" alt="">
                    <div class="card-heading"></div>
                    <button class="btn btn-danger btn-xs">See the Detail</button>
                  </div>
                </div><br>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="pet-title">Utin</h5>
                    <img src="image/dog/Dog1.jpg" class="pet-img card-img-bottom" alt="">
                    <div class="card-heading"></div>
                    <button class="btn btn-danger btn-xs">See the Detail</button>
                  </div>
                </div><br>
              </div> 
            </div>
            <nav aria-label="Page navigation example">
              <ul class="pagination justify-content-end">
                <li class="page-item disabled">
                  <a class="page-link">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">Next</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <div class="col-md-1"></div>
      </div>
    </div>
    <br>

@endsection