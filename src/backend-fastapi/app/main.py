from fastapi import FastAPI
import models
from routers import signalements
from database import engine

# Création des tables dans la base de données
models.Base.metadata.create_all(bind=engine)

app = FastAPI(title="API Backend de l'Application")

# Inclusion des routeurs
app.include_router(signalements.routeur)

@app.get("/")
def accueil():
    return {"statut": "Le serveur backend est en ligne"}