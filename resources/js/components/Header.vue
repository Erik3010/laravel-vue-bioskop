<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <router-link to="/" tag="li">
                        <a class="nav-link">Branch</a>
                    </router-link>
                    <router-link to="/studio" tag="li">
                        <a class="nav-link">Studio</a>
                    </router-link>
                     <router-link to="/movie" tag="li">
                        <a class="nav-link">Movie</a>
                    </router-link>
                     <router-link to="/schedule" tag="li">
                        <a class="nav-link">Schedule</a>
                    </router-link>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    
}
</script>