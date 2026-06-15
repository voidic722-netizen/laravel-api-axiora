# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {ADMIN_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Obtain a token via `POST /api/auth/login`, then send it as `Authorization: Bearer {token}`.
