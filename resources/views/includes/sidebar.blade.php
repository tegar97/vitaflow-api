<div class="col-12 col-lg-3 col-navbar d-none d-xl-block">
    <aside class="sidebar">
        <a href="#" class="sidebar-logo">
            <div class="d-flex justify-content-start align-items-center">
                <span>VitaFlow</span>
            </div>

            <button id="toggle-navbar" onclick="toggleNavbar()">
                <img src="./assets/img/global/navbar-times.svg" alt="" />
            </button>
        </a>

        <h5 class="sidebar-title">Food Management</h5>

        {{-- <a href="./index.html" class="sidebar-item{{ Request::is('/') ? ' active' : '' }}" onclick="toggleActive(this)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Overview</span>
        </a> --}}

        <!-- <a href="./employees.html" class="sidebar-item"> -->
        <!-- <img src="./assets/img/global/users.svg" alt=""> -->
        <a href="{{ route('foodServing.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'foodServing' ? ' active' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Food Serving size</span>
        </a>
 <a href="{{ route('food.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'food' ? ' active' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Food </span>
        </a>

<h5 class="sidebar-title">Exercise</h5>

        {{-- <a href="./index.html" class="sidebar-item{{ Request::is('/') ? ' active' : '' }}" onclick="toggleActive(this)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Overview</span>
        </a> --}}

        <!-- <a href="./employees.html" class="sidebar-item"> -->
        <!-- <img src="./assets/img/global/users.svg" alt=""> -->
        <a href="{{ route('exercise.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'exercise' ? ' active' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Exercise Program</span>
        </a>
  <a href="{{ route('exerciseType.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'exerciseType' ? ' active' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Gerakan Olahraga</span>
        </a>


    </aside>
</div>
