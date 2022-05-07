$( ".imagen" ).on( "click", function() {
    imgcolor($(this).children('#src').val());
  });

function imgcolor(src) {
var image = new Image();
image.src = src;
image.onload = function() {
var rgb = getaverageColor(this);
$('.modalimg').css("background",'rgb('+(rgb[0]+100)+','+(rgb[1]+100)+','+(rgb[2]+100)+',0.5)');
}
function getaverageColor(imagen) {
   var r=0, g=0, b=0, count = 0, canvas, ctx, imageData, data, i;
   canvas = document.createElement('canvas');
   ctx = canvas.getContext("2d");
   canvas.width = imagen.width;
   canvas.height = imagen.height;
   ctx.drawImage(imagen, 0, 0);
   imageData = ctx.getImageData(0, 0, imagen.width, imagen.height);
   data = imageData.data;
   for(i = 0, n = data.length; i < n; i += 4) {
       ++count;
       r += data[i];
       g += data[i+1];
       b += data[i+2];
   }
   r = ~~(r/count);
   g = ~~(g/count);
   b = ~~(b/count);
   return [r, g, b];
}
}