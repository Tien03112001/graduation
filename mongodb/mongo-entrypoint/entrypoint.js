var db = connect("mongodb://root:h0a11GB4o098@localhost:27017/admin?authSource=admin");

db.createUser(
    {
        user: "logger",
        pwd: "h0a11GB4o098",
        roles: [
            {role: "readWrite", db: "logging"}
        ]
    }
);