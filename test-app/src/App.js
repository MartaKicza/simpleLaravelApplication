import React, {useState, useEffect} from 'react';
import useDataApi from './DataApi';

import './App.css';
import UsersList from './UsersList';
import User from './User';
import Button from './Button';
import UserAddForm from './UserAddForm';


let userId = 0;
function App() {

  const showUrl = 'http://localhost:4040/api/user/';
  const deleteUrl = 'http://localhost:4040/api/user/';
  const [{ data, isLoading, isError }, doUserFetch] = useDataApi(
    showUrl,
    {},
    {},
    'get'
  );

  const [userEdited, setUserEdited] = useState(false);

  useEffect(() => {
    if (userEdited) doUserFetch(userId);
  }, [userEdited, doUserFetch]);

  const [{ data: deleteData}, doUserDelete] = useDataApi(
    deleteUrl,
    {},
    {},
    'delete'
  );
  const [addingUser, setAddingUser] = useState(false);

  const createUserButton = <Button 
          clickAction={() => {setAddingUser(true)}}
          className="Button-success"
        >Add new</Button>;
  return (
    <div className="App">
      <div className="App-header"><h1>Simple application</h1></div>
      { data.user && <User 
        user={data.user} 
        isLoading={isLoading} 
        isError={isError} 
        deleted={deleteData} 
        userId={userId}
        userLogId={(id) => userId = id}
        onUserDeleteClick={(user) => {
          doUserDelete(deleteUrl + user.id); 
        }}
        onUserEdited={() => setUserEdited(true)}/> }
      <UsersList onUserClick={(row) => {
        doUserFetch(showUrl + row.id); 
      }}/>
      { addingUser && <UserAddForm />}
      { !addingUser && <div className="App-conainer">
          {createUserButton} 
      </div>}
    </div>
  );
}

export default App;
