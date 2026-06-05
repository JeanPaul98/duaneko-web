"""
dependencies.py – FastAPI dependencies for authentication and authorisation.

Exports:
    get_current_user       – extracts the user from the JWT token.
    require_permission     – factory that creates a dependency checking a specific
                             permission. Usage: Depends(require_permission("api:receipts.list"))
    get_current_admin_user – convenience dependency that requires a core admin
                             permission (still works with new roles).
"""

from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from jose import JWTError, jwt
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from sqlalchemy.orm import selectinload

from app.database import get_db
from app.models import User, RolePermission
from app.schemas import TokenData
from app.config import settings   # assumes SECRET_KEY and ALGORITHM

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/auth/login")


async def get_current_user(
    token: str = Depends(oauth2_scheme),
    db: AsyncSession = Depends(get_db),
) -> User:
    """
    Decode the JWT, fetch the corresponding User including the joined Role.
    """
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(
            token, settings.SECRET_KEY, algorithms=[settings.ALGORITHM]
        )
        user_id: str = payload.get("sub")
        if user_id is None:
            raise credentials_exception
        token_data = TokenData(user_id=user_id)
    except JWTError:
        raise credentials_exception

    # Load user with role eagerly
    result = await db.execute(
        select(User)
        .options(selectinload(User.role))
        .where(User.id == token_data.user_id)
    )
    user = result.scalars().first()
    if user is None:
        raise credentials_exception
    return user


def require_permission(permission_key: str):
    """
    Factory that returns a dependency which checks if the current user's role
    has the given ``permission_key`` granted.
    """
    async def permission_dependency(
        current_user: User = Depends(get_current_user),
        db: AsyncSession = Depends(get_db),
    ) -> User:
        result = await db.execute(
            select(RolePermission)
            .where(
                RolePermission.role_id == current_user.role_id,
                RolePermission.permission_key == permission_key,
                RolePermission.granted == True,
            )
        )
        if not result.scalars().first():
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail="Insufficient permissions",
            )
        return current_user

    return permission_dependency


# Convenience: a pre-configured dependency that requires at least one admin page permission.
# You can still use require_permission directly with the exact needed key.
async def get_current_admin_user(
    current_user: User = Depends(require_permission("page:admin.company")),
):
    return current_user