from sqlalchemy import Column, Integer, String, Float, ForeignKey, DateTime
from sqlalchemy.sql import func
from sqlalchemy.orm import relationship
from database import Base

class Utilisateur(Base):
    __tablename__ = "utilisateurs"

    id = Column(Integer, primary_key=True, index=True)
    email = Column(String, unique=True, index=True)
    mot_de_passe_hache = Column(String)
    
    signalements = relationship("Signalement", back_populates="auteur")

class Signalement(Base):
    __tablename__ = "signalements"

    id = Column(Integer, primary_key=True, index=True)
    titre = Column(String, index=True)
    description = Column(String)
    latitude = Column(Float)  
    longitude = Column(Float)
    url_image = Column(String) 
    cree_le = Column(DateTime(timezone=True), server_default=func.now())
    
    auteur_id = Column(Integer, ForeignKey("utilisateurs.id"))
    auteur = relationship("Utilisateur", back_populates="signalements")