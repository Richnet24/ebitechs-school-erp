<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ebitechs School ERP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .logo {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .admin-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .admin-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .info {
            margin-top: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .credentials {
            margin-top: 1rem;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🎓</div>
        <h1>Ebitechs School ERP</h1>
        <p class="subtitle">Système de Gestion Scolaire</p>
        
        <a href="/admin/login" class="admin-btn">
            🔐 Accéder à l'Administration
        </a>
        
        <div class="info">
            <p><strong>Identifiants par défaut :</strong></p>
            <div class="credentials">
                Email: admin@ebitechs.edu<br>
                Mot de passe: password
            </div>
        </div>
    </div>
</body>
</html>
