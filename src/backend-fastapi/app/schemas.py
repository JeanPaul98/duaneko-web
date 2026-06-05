from pydantic import BaseModel
from datetime import datetime

class SignalementBase(BaseModel):
    titre: str
    description: str
    latitude: float
    longitude: float
    url_image: str | None = None

class SignalementCreation(SignalementBase):
    pass

class SignalementSortie(SignalementBase):
    id: int
    cree_le: datetime
    auteur_id: int

    class Config:
        from_attributes = True