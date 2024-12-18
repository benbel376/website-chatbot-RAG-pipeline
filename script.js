'use strict';

// slide show related code.
document.addEventListener('DOMContentLoaded', function() {
  const initSlideshow = (container) => {
    const slides = container.querySelectorAll('.slideshow-slide');
    const dots = container.querySelectorAll('[data-dot]');
    const nextBtn = container.querySelector('[data-next]');
    const prevBtn = container.querySelector('[data-prev]');
    let currentSlide = 0;

    const showSlide = (index) => {
      slides.forEach((slide, i) => {
        slide.classList.remove('active-dot');
        dots[i].classList.remove('active-dot');
        if (i === index) {
          slide.classList.add('active-dot');
          dots[i].classList.add('active-dot');
        }
      });
    };

    const nextSlide = () => {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    };

    const prevSlide = () => {
      currentSlide = (currentSlide - 1 + slides.length) % slides.length;
      showSlide(currentSlide);
    };

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        currentSlide = index;
        showSlide(currentSlide);
      });
    });

    showSlide(currentSlide);
  };

  // Loop through all elements with the class 'slideshow-outer-container' and initialize the slideshow for each
  document.querySelectorAll('.slideshow-outer-container').forEach(container => {
    initSlideshow(container);
  });
});


//****************************************** */

document.addEventListener('DOMContentLoaded', function() {

  const initPagination = (container) => {
    let currentPage = 1;
    const pages = container.querySelectorAll('.blog-page');
    const totalPages = pages.length;
    const prevBtn = container.querySelector('[data-blog-prev-page]');
    const nextBtn = container.querySelector('[data-blog-next-page]');
    const paginationInfo = container.querySelector('.blog-pagination-info');

    const updatePagination = () => {
      // Update pagination info
      paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;

      // Show or hide the prev/next buttons
      prevBtn.disabled = currentPage === 1;
      nextBtn.disabled = currentPage === totalPages;

      // Show the current page and hide the others
      pages.forEach((page, index) => {
        if (index + 1 === currentPage) {
          page.classList.add('active');
        } else {
          page.classList.remove('active');
        }
      });
    };

    prevBtn.addEventListener('click', () => {
      if (currentPage > 1) {
        currentPage--;
        updatePagination();
      }
    });

    nextBtn.addEventListener('click', () => {
      if (currentPage < totalPages) {
        currentPage++;
        updatePagination();
      }
    });

    // Initialize pagination on page load
    updatePagination();
  };

  // Loop through all elements with the class 'blog-details-container' and initialize pagination for each
  document.querySelectorAll('.blog-details-container').forEach(container => {
    initPagination(container);
  });

});


//******************************************** */

// Element toggle function
const elementToggleFunc = function (elem) {
  elem.classList.toggle("active");
}



// skills widget related code ....................................................

// Get references to the required elements
const skillItems = document.querySelectorAll("[data-skill-item]");
const skillModalContainer = document.querySelector("[data-skill-modal-container]");
const skillModalCloseBtn = document.querySelector("[data-skill-modal-close-btn]");
const skillOverlay = document.querySelector("[data-skill-overlay]");

// Modal content elements
const skillModalImg = document.querySelector("[data-skill-modal-img]");
const skillModalTitle = document.querySelector("[data-skill-modal-title]");
const skillModalTime = document.querySelector("[data-skill-modal-time]");
const skillModalSummary = document.querySelector("[data-skill-modal-summary]");
const skillModalList = document.querySelector("[data-skill-modal-list]");

// Modal toggle function
const skillModalToggleFunc = function () {
  elementToggleFunc(skillModalContainer);
  elementToggleFunc(skillOverlay);
}

// Add click event to each skill item
skillItems.forEach((skillItem) => {
  skillItem.addEventListener("click", function () {
    const avatar = this.querySelector("[data-skill-avatar]").src;
    const avatarAlt = this.querySelector("[data-skill-avatar]").alt;
    const title = this.querySelector("h4").textContent;
    const time = this.querySelector("time").textContent;
    const summary = this.querySelector(".skill-summary").textContent;
    const listItems = this.querySelectorAll(".skill-list li");

    // Set modal content
    skillModalImg.src = avatar;
    skillModalImg.alt = avatarAlt;
    skillModalTitle.textContent = title;
    skillModalTime.textContent = time;
    skillModalSummary.textContent = summary;

    // Clear the previous list
    skillModalList.innerHTML = '';

    // Populate the list with new items
    listItems.forEach((item) => {
      const li = document.createElement('li');
      li.innerHTML = item.innerHTML; // Copy both the icon and text
      skillModalList.appendChild(li);
    });

    // Show the modal
    skillModalToggleFunc();
  });
});

// Add click event to modal close button and overlay
skillModalCloseBtn.addEventListener("click", skillModalToggleFunc);
skillOverlay.addEventListener("click", skillModalToggleFunc);


//888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888



// sidebar variables
const sidebar = document.querySelector("[data-sidebar]");
const sidebarBtn = document.querySelector("[data-sidebar-btn]");

// sidebar toggle functionality for mobile
sidebarBtn.addEventListener("click", function () { elementToggleFunc(sidebar); });


// testimonial related code ........................................


// testimonials variables
const testimonialsItem = document.querySelectorAll("[data-testimonials-item]");
const modalContainer = document.querySelector("[data-modal-container]");
const modalCloseBtn = document.querySelector("[data-modal-close-btn]");
const overlay = document.querySelector("[data-overlay]");

// modal variable
const modalImg = document.querySelector("[data-modal-img]");
const modalTitle = document.querySelector("[data-modal-title]");
const modalText = document.querySelector("[data-modal-text]");

// modal toggle function
const testimonialsModalFunc = function () {
  modalContainer.classList.toggle("active");
  overlay.classList.toggle("active");
}

// add click event to all modal items
for (let i = 0; i < testimonialsItem.length; i++) {

  testimonialsItem[i].addEventListener("click", function () {

    modalImg.src = this.querySelector("[data-testimonials-avatar]").src;
    modalImg.alt = this.querySelector("[data-testimonials-avatar]").alt;
    modalTitle.innerHTML = this.querySelector("[data-testimonials-title]").innerHTML;
    modalText.innerHTML = this.querySelector("[data-testimonials-text]").innerHTML;

    testimonialsModalFunc();

  });

}

// add click event to modal close button
modalCloseBtn.addEventListener("click", testimonialsModalFunc);
overlay.addEventListener("click", testimonialsModalFunc);


//*********************************************************************** */
function initializePortfolio(containerId) {
  const container = document.getElementById(containerId);

  // Get references to the required elements within the container
  const projectItems = container.querySelectorAll(".content-item");
  const projectListSection = container.querySelector("[data-content-list]");
  const projectDetailsSection = container.querySelector("[data-content-details]");
  const goBackBtn = container.querySelector("[data-go-back]");
  const detailUnits = container.querySelectorAll(".details-unit");

  const filterBtn = container.querySelectorAll("[data-filter-btn]");
  const sortButtons = container.querySelectorAll(".sort-btn");

  const selectButton = container.querySelector('[data-select]');
  const selectValue = container.querySelector('[data-select-value]');
  const selectList = container.querySelector('.select-list');
  const selectItems = container.querySelectorAll('[data-select-item]');
  const searchInput = container.querySelector('[data-search-input]'); // Search input

  let selectedTags = [];

  // Handle project item click
  projectItems.forEach((item) => {
    item.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent default link behavior
      
      const contentId = this.dataset.contentId;

      detailUnits.forEach((unit) => {
        unit.hidden = true;
        unit.classList.remove("active");
      });
      container.querySelector(`#${contentId}-details`).hidden = false;
      container.querySelector(`#${contentId}-details`).classList.add("active");

      projectListSection.classList.add("hidden");
      projectDetailsSection.classList.add("active");
      
      goBackBtn.hidden = false;
      goBackBtn.classList.add("visible");
    });
  });

  // Handle go back button click
  goBackBtn.addEventListener("click", function () {
    projectDetailsSection.classList.remove("active");
    projectListSection.classList.remove("hidden");

    detailUnits.forEach((unit) => {
      unit.hidden = true;
      unit.classList.remove("active");
    });

    goBackBtn.classList.remove("visible");
    setTimeout(() => {
      goBackBtn.hidden = true;
    }, 250); // Delay to match the transition duration
  });

  // Sorting Functionality
  sortButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const sortType = this.getAttribute("data-sort");
      let sortOrder = this.getAttribute("data-order");

      // Toggle the sorting order
      sortOrder = sortOrder === "asc" ? "desc" : "asc";
      this.setAttribute("data-order", sortOrder);

      // Update the UI to reflect the active button and sorting direction
      sortButtons.forEach((btn) => {
        btn.classList.add("inactive");
        btn.classList.remove("active");
        btn.querySelector("ion-icon").setAttribute("name", "arrow-down-outline");
      });

      this.classList.remove("inactive");
      this.classList.add("active");
      this.querySelector("ion-icon").setAttribute("name", sortOrder === "asc" ? "arrow-up-outline" : "arrow-down-outline");

      if (sortType === "date") {
        sortByDate(sortOrder);
      } else if (sortType === "title") {
        sortByTitle(sortOrder);
      }
    });
  });

  const sortByDate = function (order) {
    const sortedItems = Array.from(projectItems).sort((a, b) => {
      const dateA = new Date(a.dataset.date);
      const dateB = new Date(b.dataset.date);
      return order === "asc" ? dateA - dateB : dateB - dateA;
    });

    renderSortedItems(sortedItems);
  };

  const sortByTitle = function (order) {
    const sortedItems = Array.from(projectItems).sort((a, b) => {
      const titleA = a.dataset.title ? a.dataset.title.toLowerCase() : '';
      const titleB = b.dataset.title ? b.dataset.title.toLowerCase() : '';
      if (order === "asc") return titleA.localeCompare(titleB);
      else return titleB.localeCompare(titleA);
    });

    renderSortedItems(sortedItems);
  };

  const renderSortedItems = function (sortedItems) {
    const contentList = container.querySelector(".content-list-items");
    contentList.innerHTML = ""; // Clear the list
    sortedItems.forEach((item) => {
      contentList.appendChild(item); // Append sorted items
    });
  };

  // Handle dropdown select for tag filtering
  selectButton.addEventListener("click", function () {
    this.classList.toggle('active');
    selectList.classList.toggle('active');
  });

  // Handle the change event on each select item for tag filtering
  selectItems.forEach((item) => {
    item.addEventListener('change', function () {
      const tag = this.value;

      if (this.checked) {
        selectedTags.push(tag);
      } else {
        selectedTags = selectedTags.filter(t => t !== tag);
      }

      updateSelectValue();
      filterProjects();
    });
  });

  // Update the select button value based on selected tags
  function updateSelectValue() {
    if (selectedTags.length > 0) {
      selectValue.textContent = "Filtered";
    } else {
      selectValue.textContent = "Filter by tags";
    }
  }

  // Filter projects based on tags and search input (both title and tags)
  function filterProjects() {
    const searchTerm = searchInput.value.toLowerCase();

    projectItems.forEach((project) => {
      const projectTags = project.dataset.tags ? project.dataset.tags.toLowerCase().split(",") : [];
      const projectTitle = project.dataset.title ? project.dataset.title.toLowerCase() : '';
      
      // Check if the project matches selected tags
      const matchesTags = selectedTags.every(tag => projectTags.includes(tag.toLowerCase()));
      
      // Check if the project matches the search term in title or tags
      const matchesSearch = projectTitle.includes(searchTerm) || projectTags.some(tag => tag.includes(searchTerm));

      // Show the project if it matches both the selected tags and the search term
      if ((selectedTags.length === 0 || matchesTags) && matchesSearch) {
        project.classList.add("active");
      } else {
        project.classList.remove("active");
      }
    });
  }

  // Real-time search filter
  searchInput.addEventListener('input', filterProjects);

  // Close the dropdown if clicking outside
  document.addEventListener('click', function (event) {
    if (!selectButton.contains(event.target)) {
      selectButton.classList.remove('active');
      selectList.classList.remove('active');
    }
  });
}


// Example of how to initialize the portfolio functionality
initializePortfolio('portfolio-container'); // Replace 'portfolio-container-id' with the actual ID of your container
initializePortfolio('blog-container');
//********************************************************* */


// contact form variables
const form = document.querySelector("[data-form]");
const formInputs = document.querySelectorAll("[data-form-input]");
const formBtn = document.querySelector("[data-form-btn]");

// add event to all form input field
for (let i = 0; i < formInputs.length; i++) {
  formInputs[i].addEventListener("input", function () {

    // check form validation
    if (form.checkValidity()) {
      formBtn.removeAttribute("disabled");
    } else {
      formBtn.setAttribute("disabled", "");
    }

  });
}



// page navigation variables
const navigationLinks = document.querySelectorAll("[data-nav-link]");
const pages = document.querySelectorAll("[data-page]");
console.log(pages);
// add event to all nav link
for (let i = 0; i < navigationLinks.length; i++) {
  navigationLinks[i].addEventListener("click", function () {

    for (let i = 0; i < pages.length; i++) {
      if (this.innerHTML.toLowerCase() === pages[i].dataset.page) {
        console.log(this.innerHTML.toLowerCase(), "......................../n")
        pages[i].classList.add("active");
        navigationLinks[i].classList.add("active");
        window.scrollTo(0, 0);
      } else {
        pages[i].classList.remove("active");
        navigationLinks[i].classList.remove("active");
      }
    }

  });
}

/* query related code */
document.getElementById('send-btn').addEventListener('click', function () {
  sendUserQuery();  // Reuse the logic by creating a function that handles sending the query
});

document.getElementById('user-query').addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    sendUserQuery();  // Trigger the same function when Enter is pressed
  }
});

document.getElementById('clear-history-btn').addEventListener('click', function () {
  clearHistory();  // Clear the chat history
});

function sendUserQuery() {
  const userQuery = document.getElementById('user-query').value;
  if (userQuery.trim() !== '') {
    addMessage(userQuery, 'message-box', 'user');  // Display user's message
    showLoadingAnimation();  // Show the loading animation
    generateBotResponse(userQuery);  // Get bot response
    document.getElementById('user-query').value = '';  // Clear input field
  }
}

function addMessage(text, className, sender) {
  const chatHistory = document.querySelector('.chat-history');
  const messageDiv = document.createElement('div');
  const messageContent = document.createElement('div');
  const iconBox = document.createElement('div');

  messageDiv.classList.add(sender === 'user' ? 'chat-message' : 'chat-response');
  messageContent.classList.add(className);
  iconBox.classList.add('icon-box');
  iconBox.textContent = sender === 'user' ? 'U' : 'B';

  // Set the message text content here
  messageContent.textContent = text;

  if (sender === 'bot') {
    // Append icon before the message for bot
    messageDiv.appendChild(iconBox);
    messageDiv.appendChild(messageContent);
  } else {
    // Append message then icon for user
    messageDiv.appendChild(messageContent);
    messageDiv.appendChild(iconBox);
  }

  chatHistory.appendChild(messageDiv);

  // Remove "No chat history" message
  document.querySelector('.no-history').style.display = 'none';

  // Auto-scroll to the bottom
  chatHistory.scrollTop = chatHistory.scrollHeight;
}

function showLoadingAnimation() {
  const chatHistory = document.querySelector('.chat-history');
  const loadingDiv = document.createElement('div');
  const loadingAnimation = document.createElement('span');
  
  loadingDiv.classList.add('chat-response');
  loadingAnimation.classList.add('loading-animation');
  
  // Simulate dot dot dot typing animation
  let dotCount = 0;
  const interval = setInterval(() => {
    dotCount = (dotCount + 1) % 4;  // Cycle through 0, 1, 2, 3
    loadingAnimation.textContent = '.'.repeat(dotCount);
  }, 500);

  loadingDiv.appendChild(loadingAnimation);
  chatHistory.appendChild(loadingDiv);
  
  // Save interval id to remove it later
  loadingDiv.setAttribute('data-interval', interval);

  // Auto-scroll to the bottom
  chatHistory.scrollTop = chatHistory.scrollHeight;
}

function generateBotResponse(userQuery) {
  fetch('https://bot.biniyambelayneh.com/query/', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      query: userQuery,
      top_k: 3  // Adjust as needed
    })
  })
    .then(response => response.json())
    .then(data => {
      const botResponse = data.answer || "I'm sorry, I couldn't find an answer to your question.";
      removeLoadingAnimation();  // Remove the loading animation
      addMessage(botResponse, 'response-box', 'bot');  // Add bot response
    })
    .catch(error => {
      console.error('Error:', error);
      removeLoadingAnimation();  // Remove the loading animation on error
      addMessage("Oops! Something went wrong. Please try again.", 'response-box', 'bot');
    });
}

function removeLoadingAnimation() {
  const loadingElement = document.querySelector('.loading-animation');
  if (loadingElement) {
    const intervalId = loadingElement.parentElement.getAttribute('data-interval');
    clearInterval(intervalId);  // Stop the dot animation interval
    loadingElement.parentElement.remove();  // Remove the loading animation element
  }
}

function clearHistory() {
  const chatHistory = document.querySelector('.chat-history');

  // Remove all message elements without affecting the overlay or "No chat history"
  chatHistory.querySelectorAll('.chat-message, .chat-response').forEach(message => message.remove());

  // Show the "No chat history" message again
  document.querySelector('.no-history').style.display = 'block';
}

// folder structure


/********************************* code line adder */

document.addEventListener("DOMContentLoaded", function () {
  const codeContent = document.getElementById("code-content");
  const lineNumbers = document.querySelector(".code-line-numbers");

  // Get the number of lines
  const codeLines = codeContent.textContent.trim().split("\n");

  // Set the overshoot value (e.g., 2 extra lines)
  const overshoot = 2;

  // Add line numbers with the overshoot
  lineNumbers.innerHTML = codeLines
    .map((_, i) => `<span>${i + 1}</span>`)
    .concat(Array.from({ length: overshoot }, (_, i) => `<span>${codeLines.length + i + 1}</span>`))
    .join("\n");
});

// tools table ****************************************
$(document).ready(function() {
  var table = $('#toolsTable').DataTable({
      "dom": 'rt<"info-pagination"ip>', // Custom DOM structure
      "pagingType": "full_numbers", // Enables first, previous, next, last buttons
      "lengthMenu": [5, 10, 25, 50, 100],
      "pageLength": 5,
      "language": {
          "search": "Search:",
          "lengthMenu": "Show _MENU_ entries",
          "info": "Showing _START_ to _END_ of _TOTAL_ tools",
          "paginate": {
              "first": '<<',
              "previous": '<',
              "next": '>',
              "last": '>>'
          }
      },
      "responsive": true,
      "columnDefs": [
          { "orderable": true, "targets": [0,1,2] },
          { "orderable": false, "targets": 3 }
      ],
      "drawCallback": function(settings) {
          var api = this.api();
          var pagination = $(api.table().container()).find('.dataTables_paginate');
          var totalPages = api.page.info().pages;
          var currentPage = api.page.info().page + 1; // Pages are zero-based in DataTables

          // Clear existing page number links and ellipses except for main navigation buttons
          pagination.find('a').not('.first, .previous, .next, .last').remove();
          pagination.find('span.ellipsis').remove();

          // Function to create a page link or ellipsis
          function createPaginationItem(content, isCurrent, isEllipsis) {
              if (isEllipsis) {
                  return $('<span class="ellipsis">...</span>');
              } else if (isCurrent) {
                  return $('<span class="current">' + content + '</span>');
              } else {
                  return $('<a href="#" class="page-link">' + content + '</a>').data('page', content - 1);
              }
          }

          // Pagination structure logic for 4 visible page numbers with ellipsis
          if (totalPages <= 4) {
              // Show all pages if total pages are 4 or fewer
              for (var i = 1; i <= totalPages; i++) {
                  pagination.append(createPaginationItem(i, i === currentPage, false));
              }
          } else {
              if (currentPage <= 2) {
                  // Beginning: Show first three pages, ellipsis, last page
                  for (var i = 1; i <= 3; i++) {
                      pagination.append(createPaginationItem(i, i === currentPage, false));
                  }
                  pagination.append(createPaginationItem('...', false, true));
                  pagination.append(createPaginationItem(totalPages, false, false));
              } else if (currentPage >= totalPages - 1) {
                  // End: Show first page, ellipsis, last three pages
                  pagination.append(createPaginationItem(1, false, false));
                  pagination.append(createPaginationItem('...', false, true));
                  for (var i = totalPages - 2; i <= totalPages; i++) {
                      pagination.append(createPaginationItem(i, i === currentPage, false));
                  }
              } else {
                  // Middle: Show first page, ellipsis, current page, ellipsis, last page
                  pagination.append(createPaginationItem(1, false, false));
                  pagination.append(createPaginationItem('...', false, true));
                  pagination.append(createPaginationItem(currentPage, true, false));
                  pagination.append(createPaginationItem('...', false, true));
                  pagination.append(createPaginationItem(totalPages, false, false));
              }
          }

          // Bind click events to page links for navigation
          pagination.find('a.page-link').off('click').on('click', function(e) {
              e.preventDefault();
              var page = $(this).data('page');
              table.page(page).draw(false);
          });

          // Set up navigation for main control buttons
          pagination.find('a.first').off('click').on('click', function(e) {
              e.preventDefault();
              table.page(0).draw(false); // Go to the first page
          });
          pagination.find('a.previous').off('click').on('click', function(e) {
              e.preventDefault();
              table.page('previous').draw(false); // Go to the previous page
          });
          pagination.find('a.next').off('click').on('click', function(e) {
              e.preventDefault();
              table.page('next').draw(false); // Go to the next page
          });
          pagination.find('a.last').off('click').on('click', function(e) {
              e.preventDefault();
              table.page(totalPages - 1).draw(false); // Go to the last page
          });
      }
  });

  // Connect custom search input to DataTables
  $('#customSearchBox').on('keyup change clear', function() {
      table.search(this.value).draw();
  });

  // Connect custom length select to DataTables
  $('#customLengthSelect').on('change', function() {
      var val = $(this).val();
      table.page.len(val).draw();
  });

  // Handle "more" toggle for expandable details
  $('#toolsTable').on('click', '.toggle-details', function(e) {
      e.preventDefault();
      var $this = $(this);
      var $detailsSnippet = $this.siblings('.details-snippet');
      var $detailsFull = $this.siblings('.details-full');

      if ($detailsFull.hasClass('hidden') || !$detailsFull.hasClass('show')) {
          $detailsSnippet.hide();
          $detailsFull.show().removeClass('hidden').addClass('show');
          $this.text('less');
          $this.attr('aria-expanded', 'true');
      } else {
          $detailsSnippet.show();
          $detailsFull.hide().removeClass('show').addClass('hidden');
          $this.text('more');
          $this.attr('aria-expanded', 'false');
      }
  });

  // Custom filtering function for category
  $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
          var category = $('#categoryFilter').val().toLowerCase();
          var rowCategory = data[1].toLowerCase(); // Category column

          if (category === "" || rowCategory === category) {
              return true;
          }
          return false;
      }
  );

  // Event listener for category filter
  $('#categoryFilter').on('change', function() {
      table.draw();
  });
});


// script.js

document.addEventListener('DOMContentLoaded', () => {
  // Select all card list items
  const cardItems = document.querySelectorAll('.card-item-type2');
  const userQueryInput = document.getElementById('user-query');
  const sendButton = document.getElementById('send-btn');
  const chatHistory = document.querySelector('.chat-history');
  const clearHistoryBtn = document.getElementById('clear-history-btn');

  // Function to add message to chat history
  function addMessageToChat(sender, message) {
      const messageElement = document.createElement('div');
      messageElement.classList.add('chat-message', sender);
      messageElement.textContent = message;
      chatHistory.appendChild(messageElement);
      chatHistory.scrollTop = chatHistory.scrollHeight;
  }

  // Mock function to generate bot response (replace with actual chatbot logic)
  function generateBotResponse(message) {
      // This is a placeholder. Replace with actual API call to your chatbot.
      return `You asked: "${message}". Here's what I can tell you about that!`;
  }

  // Function to send message
  function sendMessage() {
      const message = userQueryInput.value.trim();
      if (message !== "") {
          addMessageToChat('user', message);
          // Simulate chatbot response after 1 second
          setTimeout(() => {
              const botResponse = generateBotResponse(message);
              addMessageToChat('bot', botResponse);
          }, 1000);
          userQueryInput.value = "";
      }
  }

  // Event listener for send button
  sendButton.addEventListener('click', () => {
      sendMessage();
  });

  // Event listener for Enter key
  userQueryInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
          e.preventDefault(); // Prevent form submission if inside a form
          sendMessage();
      }
  });

  // Event listener for clear history button
  clearHistoryBtn.addEventListener('click', () => {
      chatHistory.innerHTML = '<div class="no-history">No chat history</div>';
  });

  // Event listeners for list items to inject prompts into the input field
  cardItems.forEach(item => {
      item.addEventListener('click', () => {
          const prompt = item.getAttribute('data-prompt');
          if (prompt) {
              userQueryInput.value = prompt;
              userQueryInput.focus(); // Optional: Focus the input for immediate editing
          }
      });
  });
});
