import React from 'react';

import Table from './Table';
import useDataApi from './DataApi';

function UsersList(props) {

  const indexUrl = 'http://localhost:4040/api/users';
  const [{ data, isLoading, isError }, doIndexFetch] = useDataApi(
    indexUrl,
    {data: []},
    {},
    'get'
  );
  const rows = data.data;

  const headers = isLoading || rows.length === 0 ? [] :  
    Object.keys(rows[0]).map(el => {
      if (['id', 'created_at', 'updated_at', 'address_id', 'correspondal_address_id'].indexOf(el) === -1) {
        return { name: el, show: true };
      } else {
        return { name: el, show: false };
      }
    });

  return <div className="App-conainer UsersList">
    {isError && <div>An error occured</div>}
    {isLoading ? (
      <div>Loading...</div>
    ) : (
        <div className="App-block">
          <h2 className="App-heading">Users list</h2>
          { rows.length > 0 ? (
            <Table headers={headers} {...data} fetchAction={doIndexFetch} onUserClick={props.onUserClick}/>
          ) : (
            <div>No results</div>
          )}
        </div>
    )}
  </div>
};

export default UsersList;
