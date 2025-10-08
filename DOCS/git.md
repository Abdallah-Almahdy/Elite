flowchart TD
    A[Developer: Gets Latest Version<br>git checkout main<br>git pull origin main] --> B[Developer: Creates Feature Branch<br>git checkout -b feat/new-feature];
    B --> C[Developer: Works on the Branch<br>Adds commits, pushes code];
    C --> D[Developer: Opens Pull Request<br>PR: feat/new-feature -> main];
    D --> E[You: Review the PR<br>Check code, comment, request changes];
    E -- Requests Changes --> C;
    E -- Approves PR --> F[Merge PR into main<br>Squash & Merge or Rebase];
    F --> G[Developer: Delete Branch<br>Update local main git checkout main<br>git pull origin main];
