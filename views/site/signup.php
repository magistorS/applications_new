<?php

$this->title = 'Регистрация';
?>
  <span class="title">INTERSETION</span>
<div class="containers">


</div>

<style>
  .containers {
    display: flex;
    justify-content: center;
    align-items: center;

    position: absolute;
    top: 0;
    bottom: 0px;
    margin-bottom: 150px;
    z-index: -343;
    left: 0;
    height: 500px;
  }

  .title {
    font-size: 50px;
    color: white;
    position: relative;
    top: 91px;
  }

  canvas {
    width: 100%;
  }
</style>

<script>
  //добавление элемента canvas
  var canvas = document.createElement("canvas");

  // захват экрана и его ограничение
  var width = canvas.width = window.innerWidth;
  var height = canvas.height = window.innerHeight;
  document.body.appendChild(canvas);
  var gl = canvas.getContext('webgl');
  // получение позиции мишки при входе
  var mouse = {
    x: 0,
    y: 0
  };

  var numMetaballs = 30;
  var metaballs = [];
  //рандомное расположение элементов canvas
  for (var i = 0; i < numMetaballs; i++) {
    var radius = Math.random() * 60 + 10;
    metaballs.push({
      // фиксированное значение которое позволяет радиусу менятся то есть увеличиваться или уменьшатся 
      x: Math.random() * (width - 5 * radius) + radius,
      y: Math.random() * (height - 5 * radius) + radius,
      vx: (Math.random() - 0.5) * 1,
      vy: (Math.random() - 0.5) * 1,
      r: radius * 0.75
    });
  }
  // обнуление которое я спиздил
  var vertexShaderSrc = `
attribute vec2 position;

void main() {
// position specifies only x and y.
// We set z to be 0.0, and w to be 1.0
gl_Position = vec4(position, 0.0, 1.0);
}
`;

  var fragmentShaderSrc = `
precision highp float;

const float WIDTH = ` + (width >> 0) + `.0;
const float HEIGHT = ` + (height >> 0) + `.0;

uniform vec3 metaballs[` + numMetaballs + `];

void main(){
float x = gl_FragCoord.x;
float y = gl_FragCoord.y;

float sum = 0.0;
for (int i = 0; i < ` + numMetaballs + `; i++) {
vec3 metaball = metaballs[i];
float dx = metaball.x - x;
float dy = metaball.y - y;
float radius = metaball.z;

sum += (radius * radius) / (dx * dx + dy * dy);
}

if (sum >= 0.99) {
gl_FragColor = vec4(mix(vec3(x / WIDTH, y / HEIGHT, 1.0), vec3(0, 0, 0), max(0.0, 1.0 - (sum - 0.99) * 100.0)), 1.0);
return;
}

gl_FragColor = vec4(0.0, 0.0, 0.0, 1.0);
}

`;
  // захват экрана которое позволяет с помощью обнуления обновлять изменения при расширении или обнулении
  var vertexShader = compileShader(vertexShaderSrc, gl.VERTEX_SHADER);
  var fragmentShader = compileShader(fragmentShaderSrc, gl.FRAGMENT_SHADER);

  var program = gl.createProgram();
  gl.attachShader(program, vertexShader);
  gl.attachShader(program, fragmentShader);
  gl.linkProgram(program);
  gl.useProgram(program);

  var vertexData = new Float32Array([
    -1.0, 1.0, // top left
    -1.0, -1.0, // bottom left
    1.0, 1.0, // top right
    1.0, -1.0, // bottom right
  ]);
  var vertexDataBuffer = gl.createBuffer();
  gl.bindBuffer(gl.ARRAY_BUFFER, vertexDataBuffer);
  gl.bufferData(gl.ARRAY_BUFFER, vertexData, gl.STATIC_DRAW);
  // фиксированное изменение позиции
  var positionHandle = getAttribLocation(program, 'position');
  gl.enableVertexAttribArray(positionHandle);
  gl.vertexAttribPointer(positionHandle,
    2,
    gl.FLOAT,
    gl.FALSE,
    2 * 4,
    0
  );

  var metaballsHandle = getUniformLocation(program, 'metaballs');

  loop();
  // эта функция скрытая, которая позволяет начать двигатться элементам которые сами рисуются 
  function loop() {
    for (var i = 0; i < numMetaballs; i++) {
      var metaball = metaballs[i];
      metaball.x += metaball.vx;
      metaball.y += metaball.vy;

      if (metaball.x < metaball.r || metaball.x > width - metaball.r) metaball.vx *= -1;
      if (metaball.y < metaball.r || metaball.y > height - metaball.r) metaball.vy *= -1;
    }
    //изменение флота элемента на то которое было в обнулении 
    // это позволяет объектам появляться в рандомных местах
    var dataToSendToGPU = new Float32Array(3 * numMetaballs);
    for (var i = 0; i < numMetaballs; i++) {
      var baseIndex = 3 * i;
      var mb = metaballs[i];
      dataToSendToGPU[baseIndex + 0] = mb.x;
      dataToSendToGPU[baseIndex + 1] = mb.y;
      dataToSendToGPU[baseIndex + 2] = mb.r;
    }
    gl.uniform3fv(metaballsHandle, dataToSendToGPU);

    //Draw
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);

    requestAnimationFrame(loop);
  }
  // получает source элементов то есть фиксировать их в рандоме 
  function compileShader(shaderSource, shaderType) {
    var shader = gl.createShader(shaderType);
    gl.shaderSource(shader, shaderSource);
    gl.compileShader(shader);

    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
      throw "Shader compile failed with: " + gl.getShaderInfoLog(shader);
    }

    return shader;
  }

  function getUniformLocation(program, name) {
    var uniformLocation = gl.getUniformLocation(program, name);
    if (uniformLocation === -1) {
      throw 'Can not find uniform ' + name + '.';
    }
    return uniformLocation;
  }
  // получает значение элементов 

  function getAttribLocation(program, name) {
    var attributeLocation = gl.getAttribLocation(program, name);
    // если поставить плюсовое значение то пупырки будут бесконечнв и заполнят весь экран
    if (attributeLocation === -1) {
      throw 'Can not find attribute ' + name + '.';
    }
    return attributeLocation;
  }

  canvas.onmousemove = function(e) {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
  }
</script>