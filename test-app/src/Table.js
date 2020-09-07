import React from 'react';
import PropTypes from 'prop-types';
import Button from './Button';
import './Table.css';

function Table(props) {

  if (props.data.length === 0) {
    return <h2>No data</h2>;
  }

  return <div>
    <div className="Table-containter">
      <table className="Table">
        <thead>
          <tr><th key="0">Id</th>{ props.headers.filter(el => el.show).map((el, index) => <th key={index}>{el.name}</th>) }</tr>
        </thead>
        <tbody>{
          props.data.map((row, row_index) => {
            const flatRow = Object.values(row);
            return <tr key={row_index} onClick={props.onUserClick.bind(null, row)}>
              <td key={row_index}>{props.from + row_index}</td>
              {flatRow.filter((el, id) => props.headers[id].show).map((el, index) => {
                if (typeof el === 'number') {
                  return <td key={index}>{el === 0 ? 'No' : 'Yes'}</td>
                }
                if (el !== null && typeof el === 'object') {
                  return <td key={index}>
                    {el.street}
                    {' '}
                    {el.number}
                    <br />
                    {el.code}
                    {' '}
                    {el.city}
                    <br />
                    {el.region}
                    {' '}
                    {el.country}
                  </td>
                }
                return <td key={index}>{el}</td>
              })}
            </tr>
          })
        }</tbody>
      </table>
  </div>
  <div className="table-pagination">
    <Button 
      clickAction={() => props.fetchAction(props.first_page_url)}
      className="first-page"
    >First page</Button>
    {props.prev_page_url 
      ? <Button 
          clickAction={() => props.fetchAction(props.prev_page_url)}
          className="prev-page"
        >Previous</Button> 
      : null}
    {props.next_page_url 
      ? <Button 
          clickAction={() => props.fetchAction(props.next_page_url)}
          className="next-page"
        >Next</Button> 
      : null}
  </div>
</div>
}

Table.propTypes = {
  data: PropTypes.arrayOf(PropTypes.object).isRequired,
  headers: PropTypes.arrayOf(PropTypes.exact({name: PropTypes.string, show: PropTypes.bool})).isRequired,
  from: PropTypes.number,
  to: PropTypes.number,
  per_page: PropTypes.number,
  next_page_url: PropTypes.string,
  prev_page_url: PropTypes.string,
  first_page_url: PropTypes.string,
}

Table.defaultProps = {
  data: [],
  headers: [],
  from: 1,
  top: 1,
  per_page: 1,
  next_page_url: '',
  prev_page_url: '',
  first_page_url: '',
}

export default Table;

