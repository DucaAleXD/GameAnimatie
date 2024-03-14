    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                margin: 0;
                overflow: hidden;
            }
            canvas {
                display: block;
                background-color: #f0f0f0;
            }
        </style>
        <title>Joc de eschivare</title>
    </head>
    <body>
        <canvas id="gameCanvas" width="800" height="600"></canvas>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var canvas = document.getElementById("gameCanvas");
                var ctx = canvas.getContext("2d");

                var player = {
                    x: 50,
                    y: canvas.height / 2,
                    width: 20,
                    height: 20,
                    color: "#50BEFA",
                    speed: 15,
                    draw: function () {
                        ctx.fillStyle = this.color;
                        ctx.fillRect(this.x, this.y, this.width, this.height);
                    }
                };

                var obstacles = [];
                var obstacleSpeed = 3;
                var obstacleSpawnRate = 2000; // Intervalul în milisecunde pentru apariția unui nou obstacol
                var lastSpawn = 0;
                var score = 0;
                var minObstacleDistance = 100; 

                function update() {
                    // Mișcarea jucătorului
                    if ((keys && keys.ArrowUp) || keys.w) {
                        player.y -= player.speed;
                    }
                    if ((keys && keys.ArrowDown) || keys.s) {
                        player.y += player.speed;
                    }
                    // Limitarea jucătorului la limitele canvasului
                    if (player.y < 0) {
                        player.y = 0;
                    }
                    //nU PRMITE SA IASA DIN CANVAS
                    if (player.y > canvas.height - player.height) {
                        player.y = canvas.height - player.height;
                    }

                    // Mișcarea obstacolelor
                    for (var i = 0; i < obstacles.length; i++) {
                        obstacles[i].x -= obstacleSpeed;
                        if (obstacles[i].x + obstacles[i].width < 0) {
                            obstacles.splice(i, 1);
                            i--;
                        }
                    }

                    // Apariția de noi obstacole
                    if (Date.now() - lastSpawn > obstacleSpawnRate) {
                        lastSpawn = Date.now();
                        var obstacleHeight = Math.random() * (canvas.height / 2 - 50) + 50; // Înălțimea între 50 și jumătate din înălțimea canvasului
                        var obstacleY = generateRandomObstacleY(obstacleHeight);
                        var obstacle = {
                            x: canvas.width, // Obstacolul apare de la marginea dreaptă a canvasului
                            y: obstacleY,
                            width: 30,
                            height: obstacleHeight,
                            color: "#FF0000"
                        };
                        obstacles.push(obstacle);

                        // Actualizăm scorul și creștem viteza de apariție a obstacolelor
                        score++;
                        if (score > 30) {
                            obstacleSpeed += 0.05; // Creștere mai mică a vitezei odată ce scorul depășește 30
                            obstacleSpawnRate -= 10; // Creștere mai mică a ratei de apariție odată ce scorul depășește 30
                        } else {
                            obstacleSpeed += 0.5;
                            obstacleSpawnRate -= 50;
                        }
                    }

                    // Detectarea coliziunilor cu obstacolele
                    for (var i = 0; i < obstacles.length; i++) {
                        if (player.x < obstacles[i].x + obstacles[i].width &&
                            player.x + player.width > obstacles[i].x &&
                            player.y < obstacles[i].y + obstacles[i].height &&
                            player.y + player.height > obstacles[i].y) {
                            // Coliziune detectată, poți implementa acțiunile dorite aici (cum ar fi game over)
                            alert("Ai pierdut! Scor: " + score);
                            // Pentru scopul acestei demonstrații, vom reseta jocul când se detectează o coliziune
                            obstacles = [];
                            obstacleSpeed = 3;
                            obstacleSpawnRate = 2000;
                            score = 0;
                        }
                    }

                    // Verificare pentru afișarea mesajului de felicitare
                    if (score > 50) {
                        alert("Felicitări! Ai CÂȘTIGAT EEEEEEEEEE!");
                        // Resetarea scorului și a altor parametri după afișarea mesajului
                        score = 0;
                        obstacles = [];
                        obstacleSpeed = 3;
                        obstacleSpawnRate = 2000;
                    }
                }

                function draw() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    player.draw();
                    for (var i = 0; i < obstacles.length; i++) {
                        ctx.fillStyle = obstacles[i].color;
                        ctx.fillRect(obstacles[i].x, obstacles[i].y, obstacles[i].width, obstacles[i].height);
                    }
                    // Desenăm scorul
                    ctx.fillStyle = "#000";
                    ctx.font = "20px Arial";
                    ctx.fillText("Scor: " + score, 10, 30);
                }

                var keys = {};
                window.addEventListener("keydown", function (event) {
                    keys[event.key] = true;
                });
                window.addEventListener("keyup", function (event) {
                    keys[event.key] = false;
                });

                function gameLoop() {
                    update();
                    draw();
                    requestAnimationFrame(gameLoop);
                }
                gameLoop();

                // Funcție pentru generarea poziției Y aleatorii pentru un obstacol
                function generateRandomObstacleY(obstacleHeight) {
                    var minY = 0;
                    var maxY = canvas.height - obstacleHeight;
                    var obstacleY = Math.random() * (maxY - minY) + minY;
                    // Verificăm dacă distanța față de obstacolul anterior este suficient de mare
                    for (var i = 0; i < obstacles.length; i++) {
                        if (Math.abs(obstacles[i].y - obstacleY) < minObstacleDistance) {
                            // Dacă distanța este prea mică, generăm o altă poziție Y aleatorie
                            obstacleY = Math.random() * (maxY - minY) + minY;
                            // Ne reîntoarcem la începutul listei de obstacole pentru a verifica din nou
                            i = -1;
                        }
                    }
                    return obstacleY;
                }
            });
        </script>
    </body>
    </html>
