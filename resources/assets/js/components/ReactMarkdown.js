import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Markdown from 'react-remarkable'

const ReactMarkdown = ({ title, description }) => (
  <article className="markdown-body">
    <Markdown source={`## ${title}`} />
    <Markdown source={description} />
  </article>
)

export default ReactMarkdown

if (document.getElementById('markdown')) {
    ReactDOM.render(<ReactMarkdown />, document.getElementById('markdown'));
}
