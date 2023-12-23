HTML:

<form>
  <div class="autocomplete mt-2 mb-3">
    <input type="text" class="form-control form-control-lg" id="userInput" placeholder="Search for a user">
    <div id="suggestions" class="suggestions"></div>
  </div>
</form>

javascript:

<script>
  const userInput = document.getElementById("userInput");
  const suggestionsContainer = document.getElementById("suggestions");

  userInput.addEventListener("input", function () {
    const inputText = userInput.value.toLowerCase();

    // Make an AJAX request to your server to fetch user data
    fetch(`get_usernames.php?term=${inputText}`)
      .then(response => response.json())
      .then(data => {
        displaySuggestions(data);
      })
      .catch(error => {
        console.error(error);
      });

  });

  function displaySuggestions(suggestions) {
    suggestionsContainer.innerHTML = "";

    suggestions.forEach(suggestion => {
      const suggestionElement = document.createElement("div");
      suggestionElement.classList.add("suggestion");
      suggestionElement.textContent = suggestion.email; // Modify this based on your database structure

      suggestionElement.addEventListener("click", function () {
        userInput.value = suggestion.email; // Modify this based on your database structure
        suggestionsContainer.style.display = "none";
      });

      suggestionsContainer.appendChild(suggestionElement);
    });

    if (suggestions.length > 0) {
      suggestionsContainer.style.display = "block";
    } else {
      suggestionsContainer.style.display = "none";
    }
  };

  // Close the suggestions when clicking outside the autocomplete div
  document.addEventListener("click", function (event) {
    if (!event.target.closest(".autocomplete")) {
      suggestionsContainer.style.display = "none";
    }
  });


</script>

PHP:

<?php
require("settings.php");


// Handle the AJAX request
if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];

    // Use PDO to fetch user data based on the search term
    $query = "SELECT email FROM users WHERE email LIKE :term";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':term', "%$searchTerm%", PDO::PARAM_STR);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the user data as JSON
    header('Content-Type: application/json');
    echo json_encode($users);
} else {
    // Handle other requests or provide an error response
}
?>