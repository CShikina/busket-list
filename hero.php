<?php include 'db_connect.php';
  session_start();
  $locations = getUniqueLocations($conn);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styling/style.css" />
    <title>Busket List</title>
    <style>
      .error-message {
        color: red;
        margin-top: 10px;
        text-align: center;
      }
      nav ul li a {
        text-decoration: none;
        color: inherit;
      }
      nav ul li a:hover {
        color: #555;
      }
    </style>
  </head>
  <body>
    <header>
      <nav>
        <h1>Busket List</h1>
        <ul>
          <li><a href="hero.php">Home</a></li>
          <li><a href="#about-section">About</a></li>
        </ul>
      </nav>
      <section>
        <img src="/busketList/images/img1.jpg" alt="Bus travel image" />
        <div class="overlay"></div>
        <div class="header-text">
          <h1>Busket List</h1>
          <p>Ride your way through life's bucket list!</p>
        </div>
      </section>
    </header>

    <main>
      <div class="trip-info">
        <form id="tripForm" action="bookSelection.php" method="GET">
          <div class="trip-types">
            <input
              type="radio"
              id="one-way"
              name="trip-type"
              value="one-way"
              required
            />
            <label for="one-way">One-way</label>
            <input
              type="radio"
              id="round-trip"
              name="trip-type"
              value="round-trip"
            />
          </div>

          <div class="trip-route">
            <div class="trip-origin">
              <select name="origin" id="origin" required>
                <option value="" selected disabled hidden>Origin</option>
                <?php foreach ($locations as $location): ?>
                  <option value="<?php echo htmlspecialchars($location); ?>">
                      <?php echo htmlspecialchars($location); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="trip-destination">
              <select name="destination" id="destination" required>
                <option value="" selected disabled hidden>Destination</option>
              </select>
            </div>
          </div>

          <?php 
          // Close connection at the end
          $conn->close();
          ?>

          <div class="trip-schedules">
            <div class="trip-schedule">
              <label for="depart">Depart</label>
              <input type="date" id="depart" name="depart" required />
            </div>
          </div>

          <div class="trip-passengers">
            <div class="trip-passengers-label">
              <label for="passengers"></label>
            </div>
            
          </div>
          <br />
          <div id="dateError" class="error-message"></div>
          <input type="submit" value="Submit" />
          <button type="button" id="manageBookingsBtn">
            Manage my bookings
          </button>
        </form>
      </div>
      <a href="admin.php" class="back-link" style="margin-left: 50px;"> Back to navigation page</a>

    
    </main>

    <footer id="about-section">
      <div class="footerBoxes">
        <div class="footerBox">
          <h3>Privacy Policy</h3>
          <hr />
          <p>
            We are committed to protecting your privacy. We will only use the
            information we collect about you lawfully (in accordance with the
            Data Protection Act 1998). Please read on if you wish to learn more
            about our privacy policy.
          </p>
        </div>
        <div class="footerBox">
          <h3>Terms of Service</h3>
          <hr />
          <p>By using our service, you agree to provide accurate...</p>
          <p>
            By using our service, you agree to provide accurate booking
            information and comply with our travel and cancellation policies. We
            are not liable for delays or missed trips caused by user error or
            third-party issues.
          </p>
        </div>
        <div class="footerBox">
          <h3>Help & Support</h3>
          <hr />
          <p>
            If you have any questions or need assistance, our support team is
            here to help. Contact us via email or visit our help center for
            answers to frequently asked questions.
          </p>
        </div>
      </div>
      <hr />
      <p class="copy-right">2025 Busket List</p>
    </footer>

    <div id="manageBookingsModal" class="modal">
      <div class="modal-content">
          <span class="close-button">&times;</span>
          <h2>Manage my bookings</h2>
          <div class="tab-buttons">
              <button id="changeScheduleBtn" class="active">Change Schedule</button>
              <button id="seeInvoiceBtn">See invoice</button>
          </div>
          
          <form id="changeScheduleForm">
              <div class="modal-form-group">
                  <label for="trip-ID-change">Trip ID:</label>
                  <small>(No. from the invoice presented to you)</small>
                  <input type="text" id="trip-ID-change" name="trip-ID-change" />
              </div>
              <button type="submit" class="modal-submit-button">Submit</button>
          </form>

          <form id="seeInvoiceForm" style="display: none;"> <div class="modal-form-group">
                  <label for="trip-ID-invoice">Trip ID:</label>
                  <small>(No. from the invoice presented to you)</small>
                  <input type="text" id="trip-ID-invoice" name="trip-ID-invoice" />
              </div>
              <button type="submit" class="modal-submit-button">Submit</button>
          </form>
      </div>
    </div>

    <script>
      function decrementValue() {
        const input = document.getElementById("passengers");
        if (parseInt(input.value) > 1) {
          input.value = parseInt(input.value) - 1;
        }
      }

      function incrementValue() {
        const input = document.getElementById("passengers");
        input.value = parseInt(input.value) + 1;
      }

      const modal = document.getElementById("manageBookingsModal");
      const btn = document.getElementById("manageBookingsBtn");
      const span = document.getElementsByClassName("close-button")[0];

      btn.onclick = function () {
        modal.style.display = "flex";
      };

      span.onclick = function () {
        modal.style.display = "none";
      };

      window.onclick = function (event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      };

      // Prevent past date selection
      const today = new Date().toISOString().split("T")[0];
      document.getElementById("depart").setAttribute("min", today);

      document
        .getElementById("tripForm")
        .addEventListener("submit", function (e) {
          const depart = document.getElementById("depart").value;
          const returnDate = document.getElementById("return").value;
          const errorDiv = document.getElementById("dateError");
          errorDiv.textContent = "";

          if (returnDate && returnDate <= depart) {
            e.preventDefault();
            errorDiv.textContent =
              "Return date must be after the departure date.";
          }
        });

      document.getElementById("origin").addEventListener("change", function () {
  const origin = this.value;

        fetch("get_destinations.php?origin=" + encodeURIComponent(origin))
          .then((response) => response.json())
          .then((data) => {
            const destinationSelect = document.getElementById("destination");
            destinationSelect.innerHTML =
              '<option value="" selected disabled hidden>Destination</option>';

            data.forEach(function (destination) {
              const option = document.createElement("option");
              option.value = destination;
              option.textContent = destination;
              destinationSelect.appendChild(option);
            });
          })
          .catch((error) => {
            console.error("Error fetching destinations:", error);
          });
      });

      const changeScheduleBtn = document.getElementById('changeScheduleBtn');
      const seeInvoiceBtn = document.getElementById('seeInvoiceBtn');
      const changeScheduleForm = document.getElementById('changeScheduleForm');
      const seeInvoiceForm = document.getElementById('seeInvoiceForm');

      changeScheduleBtn.addEventListener('click', function() {
          // Set active class
          changeScheduleBtn.classList.add('active');
          seeInvoiceBtn.classList.remove('active');

          // Show/hide forms
          changeScheduleForm.style.display = 'block';
          seeInvoiceForm.style.display = 'none';
      });

      seeInvoiceBtn.addEventListener('click', function() {
          // Set active class
          seeInvoiceBtn.classList.add('active');
          changeScheduleBtn.classList.remove('active');

          // Show/hide forms
          changeScheduleForm.style.display = 'none';
          seeInvoiceForm.style.display = 'block';
      });

    </script>
  </body>
</html>