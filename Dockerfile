# Gunakan gambar resmi Ruby sebagai dasar
FROM ruby:3.0.0

# Set lingkungan produksi
# ENV RAILS_ENV production
# ENV RACK_ENV production

# Instal dependencies
RUN apt-get update -qq && apt-get install -y nodejs postgresql-client

# Set direktori kerja di dalam container
WORKDIR /app

# Salin Gemfile dan Gemfile.lock ke dalam container
COPY Gemfile Gemfile.lock /app/

# Instal gems
RUN bundle install 
#--deployment --without development test

# Salin kode aplikasi ke dalam container
COPY . /app/

# Precompile aset
# RUN bundle exec rails assets:precompile

# Jalankan migrasi database
# RUN bundle exec rails db:migrate

# Expose port 3000 (jika Anda ingin mengakses server Rails dari luar container)
# EXPOSE 3000

# Command untuk menjalankan server secara manual (Anda dapat menyesuaikan opsi sesuai kebutuhan)
CMD ["bash"]
