import React from "react";
import Tabs from "./components/Tabs";
import "./App.css";
import "bootstrap/dist/css/bootstrap.min.css";

function App() {
  return (
    <main className="container" id="start-screen">
      <h1 id="uno-heading">Uno Web</h1>
      <h5 id="uno-subheading">AN OPEN-SOURCE GAME</h5>
      <Tabs selected="Croc">
        <div label="Create Lobby">
          <form method="post">
            <div className="form-group">
              <label for="lname">Lobby name: </label>
              <input
                id="lname"
                name="lname"
                type="text"
                className="form-control"
                placeholder="e.g. henrisLobby112"
              />
            </div>
            <div className="form-group">
              <label for="lpwd">Lobby password: </label>
              <input
                id="lpwd"
                name="lpwd"
                type="password"
                className="form-control"
                placeholder="e.g. password212"
              />
              <small id="reminder" className="form-text text-muted">
                Make sure to remember the lobbys name and password!
              </small>
            </div>

            <button type="submit">Create Lobby</button>
          </form>
        </div>
        <div label="Join Lobby">
          <form method="post">
            <div className="form-group">
              <label for="lname">Lobby name: </label>
              <input
                id="lname"
                name="lname"
                type="text"
                className="form-control"
                placeholder="e.g. henrisLobby112"
              />
            </div>
            <div className="form-group">
              <label for="lpwd">Lobby password: </label>
              <input
                id="lpwd"
                name="lpwd"
                type="password"
                className="form-control"
                placeholder="e.g. password212"
              />
              <small id="reminder" className="form-text text-muted">
                Have fun while playing!
              </small>
            </div>

            <button type="submit">Join Lobby</button>
          </form>
        </div>
      </Tabs>
    </main>
  );
}

export default App;
