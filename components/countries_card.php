<section class="cards-wrapper">
        <?php foreach ($countries as $country): ?>

            <div class="card-grid-space">
                <a  class="card" 
                    style="--bg-img: url('<?php echo $country->country_img ?>');" 
                    href="posts.php?country=<?php echo urlencode($country->country) ?>"
                >
                    <div>
                        <h1><?= htmlspecialchars($country->country) ?></h1>
                    </div>
                </a>
            </div>
        <?php endforeach ?>
</section>