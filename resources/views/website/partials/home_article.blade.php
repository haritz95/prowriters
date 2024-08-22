<div class="container mb-5">
    <div class="row">       
       <div class="offset-md-2 col-md-8">         
          <h2 class="section__title h3">{!! addEmWrapper($homeArticle->title)  !!}</h2>
          <p>{{ $homeArticle->sub_title }}</p>
          <?php echo nl2br($homeArticle->getRawOriginal('additional_data')) ?>          
       </div>      
    </div>
 </div>