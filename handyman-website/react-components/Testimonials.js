class Testimonials extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            testimonials: [],
            loading: true,
            errorMessage: null,
        };
    }

    // Fetch testimonials from the server
    fetchTestimonials = () => {
        fetch('backend/getTestimonials.php')
            .then((response) => response.json())
            .then((data) => this.setState({ testimonials: data, loading: false }))
            .catch((error) => console.error('Error fetching testimonials:', error));
    };

    componentDidMount() {
        this.fetchTestimonials();
    }

    // Handle testimonial form submission
    handleFormSubmit = (event) => {
        event.preventDefault(); // Prevent default form submission
        const formData = new FormData(event.target);

        fetch('backend/saveTestimonial.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    this.fetchTestimonials(); // Refresh testimonials
                    event.target.reset(); // Clear the form
                } else {
                    this.setState({ errorMessage: data.message });
                }
            })
            .catch((error) => {
                console.error('Error submitting testimonial:', error);
                this.setState({ errorMessage: "An error occurred. Please try again." });
            });
    };

    render() {
        const { testimonials, loading, errorMessage } = this.state;

        return (
            <div>
                <form onSubmit={this.handleFormSubmit}>
                    <h3>Leave a Testimonial</h3>
                    {errorMessage && <p style={{ color: "red" }}>{errorMessage}</p>}
                    <label htmlFor="name">Name:</label>
                    <input type="text" id="name" name="name" required />
                    <label htmlFor="message">Your Testimonial:</label>
                    <textarea id="message" name="message" required></textarea>
                    <button type="submit">Submit</button>
                </form>

                <h3>What Our Customers Say</h3>
                {loading ? (
                    <p>Loading testimonials...</p>
                ) : (
                    testimonials.map((testimonial, index) => (
                        <div key={index} className="testimonial">
                            <p><strong>{testimonial.name}</strong></p>
                            <p>"{testimonial.message}"</p>
                        </div>
                    ))
                )}
            </div>
        );
    }
}

ReactDOM.render(<Testimonials />, document.getElementById('customerTestimonials'));
