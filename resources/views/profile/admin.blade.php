@extends('layout.admin')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    
<div class="page-heading">
<div class="page-title">
<div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Account Profile</h3>
        <p class="text-subtitle text-muted">A page where users can change profile information</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>
</div>
<section class="section">
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="avatar avatar-2xl">
                        <img src="https://i0.wp.com/pbs.twimg.com/media/EYFB2FJU0AA4NeQ.jpg " alt="Avatar"  style="width: 150px; height: 200px;">
                    </div>

                    <h3 class="mt-3">ASEP SAEPULLOH</h3>
                    <p class="text-small">Junior Software Engineer</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="#" method="get">
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Your Name" value="asep" fdprocessedid="pczq">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Your Email" value="asepsep.coy@example.net" fdprocessedid="cgz6v">
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Your Phone" value="083xxxxxxxxx" fdprocessedid="4hujis">
                    </div>
                    <div class="form-group">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" name="birthday" id="birthday" class="form-control" placeholder="Your Birthday">
                    </div>
                    <div class="form-group">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-control" fdprocessedid="46efzc">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" fdprocessedid="vp6voe">Save Changes</button>
                        <a href="/admin" class="btn btn-primary">Kembali</a>
                    </div>                
                </form>
            </div>
        </div>
    </div>
</div>
</section>
</div>

    <footer>
<div class="footer clearfix mb-0 text-muted">
<div class="float-start">
    <p>2023 © BARUDAK CIGS</p>
</div>
<div class="float-end">
    <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
        by BARUDAK CIGS</p>
</div>
</div>
</footer>
</div>