from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from typing import List
import models
from app.database import get_db
from ..schemas import schemas

routeur = APIRouter(
    prefix="/api/signalements",
    tags=["Signalements"]
)

@routeur.post("/", response_model=schemas.SignalementSortie)
def creer_signalement(signalement: schemas.SignalementCreation, bd: Session = Depends(get_db)):
    # ID de l'auteur codé en dur pour l'instant ; à remplacer par la dépendance JWT plus tard
    nouveau_signalement = models.Signalement(**signalement.model_dump(), auteur_id=1) 
    bd.add(nouveau_signalement)
    bd.commit()
    bd.refresh(nouveau_signalement)
    return nouveau_signalement

@routeur.get("/", response_model=List[schemas.SignalementSortie])
def obtenir_signalements(sauter: int = 0, limite: int = 100, bd: Session = Depends(get_db)):
    signalements = bd.query(models.Signalement).offset(sauter).limit(limite).all()
    return signalements